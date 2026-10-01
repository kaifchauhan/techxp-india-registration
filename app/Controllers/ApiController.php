<?php

namespace App\Controllers;

use App\Libraries\VenueWeatherService;
use App\Models\RegistrationModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * Clean REST API Controller exposing registration endpoints, summit stats, and weather
 */
class ApiController extends ResourceController
{
    protected RegistrationModel $registrationModel;
    protected VenueWeatherService $weatherService;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
        $this->weatherService    = new VenueWeatherService();
    }

    /**
     * GET /api/registrations
     * Retrieve list of registrations with search and filtering
     */
    public function index()
    {
        $search     = $this->request->getGet('search') ?? '';
        $status     = $this->request->getGet('status') ?? 'all';
        $limit      = min(max((int)($this->request->getGet('limit') ?? 20), 1), 100);
        $page       = max((int)($this->request->getGet('page') ?? 1), 1);
        $offset     = ($page - 1) * $limit;

        $filters = [
            'search'      => $search,
            'status'      => $status,
        ];

        $data  = $this->registrationModel->getFilteredRegistrations($filters, $limit, $offset);
        $total = $this->registrationModel->countFilteredRegistrations($filters);

        // Sanitize output (remove sensitive password hashes)
        $sanitized = array_map(function ($row) {
            unset($row['password_hash']);
            return $row;
        }, $data);

        return $this->respond([
            'status'     => 200,
            'success'    => true,
            'pagination' => [
                'total'        => $total,
                'page'         => $page,
                'limit'        => $limit,
                'total_pages'  => ceil($total / $limit),
            ],
            'data'       => $sanitized,
        ]);
    }

    /**
     * GET /api/registrations/{id}
     * Retrieve single registration by ID or Badge ID
     */
    public function show($id = null)
    {
        if (is_numeric($id)) {
            $registration = $this->registrationModel->find($id);
        } else {
            $registration = $this->registrationModel->where('registration_id', $id)->first();
        }

        if (!$registration) {
            return $this->failNotFound('Registration record not found.');
        }

        unset($registration['password_hash']);

        return $this->respond([
            'status'  => 200,
            'success' => true,
            'data'    => $registration,
        ]);
    }

    /**
     * POST /api/registrations
     * Register a new attendee via REST API
     */
    public function create()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $rules = [
            'full_name'   => 'required|min_length[3]|max_length[100]',
            'email'       => 'required|valid_email|is_unique[registrations.email]',
            'mobile'      => 'required|regex_match[/^[0-9]{10}$/]|is_unique[registrations.mobile]',
            'password'    => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $registrationId = $this->registrationModel->generateRegistrationId();

        $record = [
            'registration_id'  => $registrationId,
            'full_name'        => trim($input['full_name'] ?? ''),
            'email'            => strtolower(trim($input['email'] ?? '')),
            'mobile'           => trim($input['mobile'] ?? ''),
            'company'          => trim($input['company'] ?? ''),
            'designation'      => trim($input['designation'] ?? ''),
            'country'          => trim($input['country'] ?? ''),
            'country'          => trim($input['city'] ?? ''),
            'password_hash'    => password_hash($input['password'], PASSWORD_BCRYPT),
            'status'           => 'confirmed',
            'ip_address'       => $this->request->getIPAddress(),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $insertId = $this->registrationModel->insert($record);

        if (!$insertId) {
            return $this->failServerError('Failed to save registration into the database.');
        }

        unset($record['password_hash']);
        $record['id'] = $insertId;

        return $this->respondCreated([
            'status'  => 201,
            'success' => true,
            'message' => 'Registration created successfully.',
            'data'    => $record,
        ]);
    }

    /**
     * PATCH /api/registrations/{id}/status
     * Update attendee status (e.g. check-in or cancellation)
     */
    public function updateStatus($id = null)
    {
        $registration = $this->registrationModel->find($id);
        if (!$registration) {
            return $this->failNotFound('Registration not found.');
        }

        $input = $this->request->getJSON(true) ?? $this->request->getRawInput();
        $status = $input['status'] ?? null;

        if (!in_array($status, ['confirmed', 'attended', 'cancelled'])) {
            return $this->failValidationErrors(['status' => 'Status must be one of: confirmed, attended, cancelled.']);
        }

        $update = ['status' => $status];
        if ($status === 'attended') {
            $update['check_in_time'] = date('Y-m-d H:i:s');
        } elseif ($status === 'confirmed') {
            $update['check_in_time'] = null;
        }

        $this->registrationModel->update($id, $update);

        return $this->respond([
            'status'  => 200,
            'success' => true,
            'message' => 'Status updated successfully.',
            'data'    => array_merge($registration, $update, ['password_hash' => null]),
        ]);
    }

    /**
     * GET /api/stats
     * Return summit stats overview
     */
    public function stats()
    {
        $stats = $this->registrationModel->getStats();

        return $this->respond([
            'status'  => 200,
            'success' => true,
            'data'    => $stats,
        ]);
    }

    /**
     * GET /api/venue-weather
     * Returns real-time venue weather from Open-Meteo REST API
     */
    public function venueWeather()
    {
        $weather = $this->weatherService->getVenueWeather();

        return $this->respond([
            'status'  => 200,
            'success' => true,
            'data'    => $weather,
        ]);
    }
}

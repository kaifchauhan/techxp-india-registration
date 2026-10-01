<?php

namespace App\Controllers;

use App\Models\RegistrationModel;

class AdminController extends BaseController
{
    protected RegistrationModel $registrationModel;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
    }

    /**
     * Admin Dashboard Overview
     */
    public function index(): string
    {
        $stats = $this->registrationModel->getStats();

        $data = [
            'pageTitle' => 'Admin Dashboard | TechXP India 2026',
            'stats'     => $stats,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Registrations List with Search, Filter & Pagination
     */
    public function registrations(): string
    {
        $search  = trim($this->request->getGet('search') ?? '');
        $session = trim($this->request->getGet('session') ?? '');
        $country = trim($this->request->getGet('country') ?? '');

        $page = max(1, (int) ($this->request->getGet('page') ?? 1));

        $perPage = 15;
        $offset  = ($page - 1) * $perPage;

        /*
        * Filters
        */
        $filters = [
            'search'  => $search,
            'session' => $session,
            'country' => $country,
        ];

        /*
        * Filtered registrations
        */
        $registrations = $this->registrationModel->getFilteredRegistrations(
            $filters,
            $perPage,
            $offset
        );

        /*
        * Number of filtered registrations
        */
        $totalCount = $this->registrationModel->countFilteredRegistrations(
            $filters
        );

        $totalPages = max(1, (int) ceil($totalCount / $perPage));

        /*
        * Overall registration count
        *
        * This is NOT affected by search/filter.
        */
        $totalRegistrations = $this->registrationModel->countAllResults();

        /*
        * Registrations by session
        */
        $registrationsBySession = $this->registrationModel
            ->select('session_preference, COUNT(*) as total')
            ->groupBy('session_preference')
            ->orderBy('session_preference', 'ASC')
            ->findAll();

        /*
        * Registrations by city/country
        */
        $registrationsByLocation = $this->registrationModel
            ->select('city, country, COUNT(*) as total')
            ->groupBy(['city', 'country'])
            ->orderBy('total', 'DESC')
            ->findAll();

        /*
        * Data for view
        */
        $data = [
            'pageTitle'              => 'Registration Dashboard | TechXP India',
            'registrations'          => $registrations,
            'totalCount'             => $totalCount,
            'totalRegistrations'     => $totalRegistrations,
            'page'                   => $page,
            'totalPages'             => $totalPages,
            'perPage'                => $perPage,
            'filters'                => $filters,
            'registrationsBySession' => $registrationsBySession,
            'registrationsByLocation' => $registrationsByLocation,
        ];

        return view('admin/registrations', $data);
    }



    /**
     * View Registration Details
     */
    public function details(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $registration = $this->registrationModel->find($id);

        if (!$registration) {
            return redirect()->to('/admin/registrations')->with('error', 'Registration record not found.');
        }

        $data = [
            'pageTitle'    => 'Registration Details - ' . $registration['registration_id'],
            'registration' => $registration,
        ];

        return view('admin/registration_details', $data);
    }

    /**
     * Check-in attendee fast action (marks attended)
     */
    public function checkIn(int $id)
    {
        $registration = $this->registrationModel->find($id);

        if (!$registration) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Record not found.']);
            }
            return redirect()->back()->with('error', 'Registration not found.');
        }

        $this->registrationModel->checkIn($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendee checked in successfully.',
                'status'  => 'attended',
                'time'    => date('h:i A, d M Y'),
            ]);
        }

        return redirect()->back()->with('success', 'Attendee ' . $registration['full_name'] . ' checked in successfully.');
    }

    /**
     * Update Registration Status
     */
    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');

        if (!in_array($status, ['confirmed', 'attended', 'cancelled'])) {
            return redirect()->back()->with('error', 'Invalid status selection.');
        }

        $updateData = ['status' => $status];
        if ($status === 'attended') {
            $updateData['check_in_time'] = date('Y-m-d H:i:s');
        } elseif ($status === 'confirmed') {
            $updateData['check_in_time'] = null;
        }

        $this->registrationModel->update($id, $updateData);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration status updated to ' . ucfirst($status),
                'status'  => $status,
            ]);
        }

        return redirect()->back()->with('success', 'Status updated successfully to ' . ucfirst($status));
    }

    /**
     * Delete Registration
     */
    public function delete(int $id)
    {
        $registration = $this->registrationModel->find($id);

        if (!$registration) {
            return redirect()->to('/admin/registrations')->with('error', 'Record not found.');
        }

        $this->registrationModel->delete($id);

        return redirect()->to('/admin/registrations')->with('success', 'Registration #' . $registration['registration_id'] . ' deleted.');
    }

    /**
     * Export Registrations as CSV
     */
    public function exportCsv()
    {
        $search     = $this->request->getGet('search') ?? '';
        $status     = $this->request->getGet('status') ?? 'all';

        $filters = [
            'search'      => $search,
            'status'      => $status,
        ];

        // Retrieve all matching records (up to 5000)
        $registrations = $this->registrationModel->getFilteredRegistrations($filters, 5000, 0);

        $filename = 'techxp_india_registrations_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // CSV Header row
        fputcsv($output, [
            'ID',
            'Badge ID',
            'Full Name',
            'Email Address',
            'Mobile',
            'Company',
            'Designation',
            'Status',
            'IP Address',
            'Check-In Time',
            'Registered At',
        ]);

        foreach ($registrations as $row) {
            fputcsv($output, [
                $row['id'],
                $row['registration_id'],
                $row['full_name'],
                $row['email'],
                $row['mobile'],
                $row['company'] ?? 'N/A',
                $row['designation'] ?? 'N/A',
                ucfirst($row['status']),
                $row['ip_address'] ?? 'N/A',
                $row['check_in_time'] ? date('Y-m-d H:i:s', strtotime($row['check_in_time'])) : 'Not Checked In',
                date('Y-m-d H:i:s', strtotime($row['created_at'])),
            ]);
        }

        fclose($output);
        exit;
    }
}

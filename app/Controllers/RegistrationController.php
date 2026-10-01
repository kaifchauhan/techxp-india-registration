<?php

namespace App\Controllers;

use App\Models\RegistrationModel;

class RegistrationController extends BaseController
{
    protected RegistrationModel $registrationModel;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
    }

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('is_attendee_logged_in')) {
            return redirect()->to('/event');
        }

        $data = [
            'pageTitle'      => 'Register for TechXP India 2026 Summit',
        ];

        return view('auth/register', $data);
    }


    public function store()
    {
        $rules = [
            'full_name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Please provide your full name.',
                    'min_length' => 'Your full name must be at least 3 characters long.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[registrations.email]',
                'errors' => [
                    'required'    => 'An email address is required for event confirmation.',
                    'valid_email' => 'Please provide a valid email address.',
                    'is_unique'   => 'This email address is already registered. Please log in instead.',
                ],
            ],
            'mobile' => [
                'rules'  => 'required|regex_match[/^[0-9]{10}$/]|is_unique[registrations.mobile]',
                'errors' => [
                    'required'    => 'A 10-digit mobile number is required.',
                    'regex_match' => 'Mobile number must be exactly 10 digits (e.g. 9876543210).',
                    'is_unique'   => 'This mobile number is already registered for TechXP India.',
                ],
            ],
            'company' => [
                'rules'  => 'permit_empty|max_length[100]',
            ],
            'designation' => [
                'rules'  => 'permit_empty|max_length[100]',
            ],
            'city' => [
                'rules' => 'required|in_list[Mumbai,Delhi,Bangalore,Hyderabad,Pune,Chennai]',
                'errors' => [
                    'required' => 'Please select your city.',
                    'in_list'  => 'Please select a valid city.',
                ],
            ],

            'country' => [
                'rules' => 'required|in_list[India,United States,United Kingdom,Canada]',
                'errors' => [
                    'required' => 'Please select your country.',
                    'in_list'  => 'Please select a valid country.',
                ],
            ],

            'session_preference' => [
                'rules' => 'permit_empty|in_list[Main Session,Workshop,Both]',
                'errors' => [
                    'in_list' => 'Please select a valid session preference.',
                ],
            ],

            'consent' => [
                'rules' => 'required|in_list[1]',
                'errors' => [
                    'required' => 'You must provide consent to register.',
                    'in_list'  => 'You must provide consent to register.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'A password is required for attendee portal access.',
                    'min_length' => 'Password must be at least 6 characters long.',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Please confirm your password.',
                    'matches'  => 'Passwords do not match.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'message' => 'Please fix the validation errors below.',
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Process interests
        $interestsInput = $this->request->getPost('interests');
        $interests = is_array($interestsInput) ? implode(', ', $interestsInput) : (string)$interestsInput;

        // Generate badge ID
        $registrationId = $this->registrationModel->generateRegistrationId();

        $data = [
            'registration_id'  => $registrationId,
            'full_name'        => trim((string)$this->request->getPost('full_name')),
            'email'            => strtolower(trim((string)$this->request->getPost('email'))),
            'mobile'           => trim((string)$this->request->getPost('mobile')),
            'company'          => trim((string)$this->request->getPost('company')),
            'designation'      => trim((string)$this->request->getPost('designation')),
            'city'             => trim((string) $this->request->getPost('city')),
            'country'          => trim((string) $this->request->getPost('country')),
            'session_preference' => $this->request->getPost('session_preference') ?: null,
            'consent'            => 1,
            'password_hash'    => password_hash((string)$this->request->getPost('password'), PASSWORD_BCRYPT),
            'status'           => 'confirmed',
            'ip_address'       => $this->request->getIPAddress(),
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $insertId = $this->registrationModel->insert($data);

        if (!$insertId) {
            $errorMessage = 'An unexpected database error occurred. Please try again.';
            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Automatically log in attendee
        session()->set([
            'is_attendee_logged_in' => true,
            'attendee_id'           => $insertId,
            'attendee_reg_id'       => $registrationId,
            'attendee_name'         => $data['full_name'],
            'attendee_email'        => $data['email'],
            'attendee_mobile'       => $data['mobile'],
        ]);

        if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
            return $this->response->setStatusCode(201)->setJSON([
                'success'         => true,
                'message'         => 'Registration successful! Welcome to TechXP India 2026.',
                'registration_id' => $registrationId,
                'redirect_url'    => site_url('event'),
            ]);
        }

        return redirect()->to('/event')->with('success', 'Registration successful! Your Badge ID is ' . $registrationId);
    }
}

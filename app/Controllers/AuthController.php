<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\RegistrationModel;

class AuthController extends BaseController
{
    protected RegistrationModel $registrationModel;
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
        $this->adminModel = new AdminModel();
    }

    /**
     * Attendee Login Page
     */
    public function login(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('is_attendee_logged_in')) {
            return redirect()->to('/event');
        }

        $data = [
            'pageTitle' => 'Attendee Login | TechXP India 2026',
        ];

        return view('auth/login', $data);
    }

    /**
     * Attendee Authentication (supports Email OR Mobile number)
     */
    public function authenticate()
    {
        $rules = [
            'identifier' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Please enter your registered Email or Mobile number.'],
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Please enter your password.'],
            ],
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                ]);
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $identifier = (string) $this->request->getPost('identifier');
        $password   = (string) $this->request->getPost('password');

        // Securely find user by Email OR Mobile
        $attendee = $this->registrationModel->findByEmailOrMobile($identifier);

        if (!$attendee) {
            $errorMessage = 'No registration found for that Email or Mobile number. Please check your credentials or register.';

            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        if ($attendee['status'] === 'cancelled') {
            $errorMessage = 'Your registration has been cancelled. Please contact the conference desk at support@techxp.in.';

            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(403)->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        if (!password_verify($password, $attendee['password_hash'])) {
            $errorMessage = 'Incorrect password. Please verify and try again.';

            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Establish Attendee Session
        session()->set([
            'is_attendee_logged_in' => true,
            'attendee_id'           => $attendee['id'],
            'attendee_reg_id'       => $attendee['registration_id'],
            'attendee_name'         => $attendee['full_name'],
            'attendee_email'        => $attendee['email'],
            'attendee_mobile'       => $attendee['mobile'],
        ]);

        $redirectUrl = session()->get('return_url') ?? site_url('event');
        session()->remove('return_url');

        if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Welcome, ' . $attendee['full_name'] . '! Redirecting to your attendee portal...',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'Welcome, ' . $attendee['full_name'] . '!');
    }

    /**
     * Attendee Logout
     */
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->remove([
            'is_attendee_logged_in',
            'attendee_id',
            'attendee_reg_id',
            'attendee_name',
            'attendee_email',
            'attendee_mobile',
        ]);

        return redirect()->to('/login')->with('success', 'You have been safely logged out.');
    }

    /**
     * Admin Login Page
     */
    public function adminLogin(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin');
        }

        $data = [
            'pageTitle' => 'Administrator Portal Login | TechXP India',
        ];

        return view('admin/login', $data);
    }

    /**
     * Admin Authentication
     */
    public function adminAuthenticate()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $admin = $this->adminModel->findByUsernameOrEmail($username);

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid administrator credentials.');
        }

        // Record login time
        $this->adminModel->recordLogin($admin['id']);

        session()->set([
            'is_admin_logged_in' => true,
            'admin_id'           => $admin['id'],
            'admin_username'     => $admin['username'],
            'admin_name'         => $admin['full_name'],
            'admin_role'         => $admin['role'],
        ]);

        return redirect()->to('/admin')->with('success', 'Welcome back, ' . $admin['full_name'] . '!');
    }

    /**
     * Admin Logout
     */
    public function adminLogout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->remove([
            'is_admin_logged_in',
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_role',
        ]);

        return redirect()->to('/admin/login')->with('success', 'Administrator logged out successfully.');
    }
}

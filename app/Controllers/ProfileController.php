<?php

namespace App\Controllers;

use App\Libraries\VenueWeatherService;
use App\Models\ChatMessageModel;
use App\Models\RegistrationModel;

class ProfileController extends BaseController
{
    protected RegistrationModel $registrationModel;
    protected ChatMessageModel $chatModel;
    protected VenueWeatherService $weatherService;

    public function __construct()
    {
        $this->registrationModel = new RegistrationModel();
        $this->chatModel         = new ChatMessageModel();
        $this->weatherService    = new VenueWeatherService();
    }

    /**
     * Protected Event Portal Main View
     * Accessible only by authenticated attendees (guarded by AuthFilter)
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $attendeeId = session()->get('attendee_id');
        $attendee   = $this->registrationModel->find($attendeeId);

        if (!$attendee) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Session invalid. Please log in again.');
        }

        // Live Third-Party REST API Data (Open-Meteo Weather for Bengaluru Venue)
        $weather = $this->weatherService->getVenueWeather();

        // Initial messages for the live chat component
        $recentChat = $this->chatModel->getRecentMessages(30);

        $data = [
            'pageTitle'   => 'Live Event Portal | TechXP India Summit 2026',
            'attendee'    => $attendee,
            'weather'     => $weather,
            'initialChat' => $recentChat,
        ];

        return view('event/index', $data);
    }

    /**
     * Dedicated Printable Digital Badge
     */
    public function badge(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $attendeeId = session()->get('attendee_id');
        $attendee   = $this->registrationModel->find($attendeeId);

        if (!$attendee) {
            return redirect()->to('/login');
        }

        $data = [
            'pageTitle' => 'Digital Badge - ' . $attendee['full_name'] . ' | TechXP India',
            'attendee'  => $attendee,
        ];

        return view('event/badge', $data);
    }

    /**
     * Update attendee profile details
     */
    public function updateProfile()
    {
        $attendeeId = session()->get('attendee_id');

        $rules = [
            'company'          => 'permit_empty|max_length[100]',
            'designation'      => 'permit_empty|max_length[100]',
            'experience_level' => 'permit_empty|in_list[0-2 years,3-5 years,5+ years,Executive / Founder]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid profile inputs.');
        }

        $interestsInput = $this->request->getPost('interests');
        $interests = is_array($interestsInput) ? implode(', ', $interestsInput) : (string)$interestsInput;

        $updateData = [
            'company'          => trim((string)$this->request->getPost('company')),
            'designation'      => trim((string)$this->request->getPost('designation')),
            'experience_level' => (string)$this->request->getPost('experience_level'),
            'interests'        => $interests,
        ];

        $this->registrationModel->update($attendeeId, $updateData);

        return redirect()->to('/event')->with('success', 'Profile updated successfully.');
    }
}

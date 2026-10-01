<?php

namespace App\Controllers;

use App\Libraries\VenueWeatherService;
use App\Models\RegistrationModel;

class Home extends BaseController
{
    public function index(): string
    {
        $weatherService = new VenueWeatherService();
        $weather = $weatherService->getVenueWeather();

        $registrationModel = new RegistrationModel();
        $totalRegistered = $registrationModel->countAllResults();

        $data = [
            'pageTitle'       => 'TechXP India 2026 Summit | The Premier AI & Cloud Conclave',
            'weather'         => $weather,
            'totalRegistered' => $totalRegistered,
        ];

        return view('home/index', $data);
    }
}

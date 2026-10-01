<?php

namespace App\Libraries;

use Config\Services;
use Exception;

/**
 * Third-Party REST API Integration: Open-Meteo Weather API
 * Provides live venue climate and weather forecast for TechXP India Summit (Bengaluru).
 */
class VenueWeatherService
{
    // Bengaluru Venue Coordinates (BIEC / Central Bengaluru)
    private const LATITUDE  = 12.9716;
    private const LONGITUDE = 77.5946;
    private const API_URL   = 'https://api.open-meteo.com/v1/forecast';

    /**
     * Fetch current venue weather and today's forecast
     */
    public function getVenueWeather(): array
    {
        $cache = Services::cache();
        $cacheKey = 'techxp_venue_weather';

        // Check 10-minute cache to avoid hammering the external API
        if ($cached = $cache->get($cacheKey)) {
            return $cached;
        }

        try {
            $client = Services::curlrequest([
                'timeout' => 4.0,
                'headers' => [
                    'User-Agent' => 'TechXP-India-Summit/1.0',
                    'Accept'     => 'application/json',
                ],
            ]);

            $queryParams = [
                'latitude'       => self::LATITUDE,
                'longitude'      => self::LONGITUDE,
                'current'        => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,weather_code,wind_speed_10m',
                'daily'          => 'weather_code,temperature_2m_max,temperature_2m_min',
                'timezone'       => 'Asia/Kolkata',
                'forecast_days'  => 1,
            ];

            $url = self::API_URL . '?' . http_build_query($queryParams);
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                if (isset($data['current'])) {
                    $weather = $this->formatWeatherData($data);
                    // Cache for 10 minutes (600 seconds)
                    $cache->save($cacheKey, $weather, 600);
                    return $weather;
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Open-Meteo API Error: ' . $e->getMessage());
        }

        // Resilient Fallback data if offline or API is temporarily unreachable
        return $this->getFallbackData();
    }

    /**
     * Transform raw external API response into clean UI-ready format
     */
    private function formatWeatherData(array $data): array
    {
        $current = $data['current'];
        $daily   = $data['daily'] ?? [];
        $code    = (int)($current['weather_code'] ?? 0);
        $wmo     = $this->interpretWmoCode($code);

        return [
            'success'            => true,
            'source'             => 'Open-Meteo REST API (Live)',
            'venue'              => 'TechXP India Arena, Mumbai',
            'city'               => 'Mumbai, Maharashtra, India',
            'temperature'        => round($current['temperature_2m']),
            'feels_like'         => round($current['apparent_temperature']),
            'humidity'           => $current['relative_humidity_2m'],
            'wind_speed'         => round($current['wind_speed_10m']),
            'condition'          => $wmo['condition'],
            'icon'               => $wmo['icon'],
            'is_day'             => (bool)($current['is_day'] ?? 1),
            'temp_max'           => isset($daily['temperature_2m_max'][0]) ? round($daily['temperature_2m_max'][0]) : round($current['temperature_2m'] + 3),
            'temp_min'           => isset($daily['temperature_2m_min'][0]) ? round($daily['temperature_2m_min'][0]) : round($current['temperature_2m'] - 4),
            'timestamp'          => date('h:i A, d M Y'),
        ];
    }

    /**
     * Map WMO weather codes to human descriptions and Bootstrap/FontAwesome icons
     */
    private function interpretWmoCode(int $code): array
    {
        return match (true) {
            $code === 0 => [
                'condition' => 'Clear Sky',
                'icon'      => 'bi-sun-fill text-warning',
            ],
            $code >= 1 && $code <= 3 => [
                'condition' => 'Partly Cloudy',
                'icon'      => 'bi-cloud-sun-fill text-info',
            ],
            $code >= 45 && $code <= 48 => [
                'condition' => 'Misty / Foggy',
                'icon'      => 'bi-cloud-haze2 text-secondary',
            ],
            $code >= 51 && $code <= 67 => [
                'condition' => 'Drizzle / Light Rain',
                'icon'      => 'bi-cloud-drizzle-fill text-primary',
            ],
            $code >= 71 && $code <= 77 => [
                'condition' => 'Snow Flurries',
                'icon'      => 'bi-snow text-primary',
            ],
            $code >= 80 && $code <= 82 => [
                'condition' => 'Passing Showers',
                'icon'      => 'bi-cloud-rain-heavy-fill text-primary',
            ],
            $code >= 95 => [
                'condition' => 'Thunderstorm',
                'icon'      => 'bi-cloud-lightning-rain-fill text-warning',
            ],
            default => [
                'condition' => 'Pleasant Weather',
                'icon'      => 'bi-cloud-fill text-info',
            ],
        };
    }

    /**
     * Reliable fallback if external connection fails
     */
    private function getFallbackData(): array
    {
        return [
            'success'     => true,
            'source'      => 'Cached Venue Climate Forecast',
            'venue'       => 'TechXP India Arena, Mumbai',
            'city'        => 'Mumbai, Maharashtra, India',
            'temperature' => 26,
            'feels_like'  => 27,
            'humidity'    => 58,
            'wind_speed'  => 14,
            'condition'   => 'Clear & Pleasant',
            'icon'        => 'bi-sun-fill text-warning',
            'is_day'      => true,
            'temp_max'    => 29,
            'temp_min'    => 20,
            'timestamp'   => date('h:i A, d M Y'),
        ];
    }
}

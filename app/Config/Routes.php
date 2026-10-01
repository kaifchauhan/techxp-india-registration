<?php

use CodeIgniter\Router\RouteCollection;

/**
 * TechXP India 2026 Summit - Application Routes
 * @var RouteCollection $routes
 */

// --- Public Landing Page ---
$routes->get('/', 'Home::index');

// --- Registration Routes ---
$routes->get('/register', 'RegistrationController::index');
$routes->post('/register', 'RegistrationController::store');

// --- Attendee Authentication (Email OR Mobile) ---
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');

// --- Protected Attendee Event Portal ---
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/event', 'ProfileController::index');
    $routes->get('/badge', 'ProfileController::badge');
    $routes->post('/profile/update', 'ProfileController::updateProfile');
});

// --- Live Chat Endpoints ---
$routes->get('/chat/messages', 'ChatController::getMessages');
$routes->post('/chat/send', 'ChatController::sendMessage');

// --- Administrator Authentication ---
$routes->get('/admin/login', 'AuthController::adminLogin');
$routes->post('/admin/login', 'AuthController::adminAuthenticate');
$routes->get('/admin/logout', 'AuthController::adminLogout');

// --- Protected Admin Portal ---
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('registrations', 'AdminController::registrations');
    $routes->get('registrations/export', 'AdminController::exportCsv');
    $routes->get('registrations/(:num)', 'AdminController::details/$1');
    $routes->post('registrations/(:num)/checkin', 'AdminController::checkIn/$1');
    $routes->post('registrations/(:num)/status', 'AdminController::updateStatus/$1');
    $routes->post('registrations/(:num)/delete', 'AdminController::delete/$1');
});

// --- Clean REST API Endpoints ---
$routes->group('api', static function ($routes) {
    $routes->get('registrations', 'ApiController::index');
    $routes->get('registrations/(:any)', 'ApiController::show/$1');
    $routes->post('registrations', 'ApiController::create');
    $routes->patch('registrations/(:num)/status', 'ApiController::updateStatus/$1');
    $routes->get('stats', 'ApiController::stats');
    $routes->get('venue-weather', 'ApiController::venueWeather');
    $routes->get('chat/messages', 'ChatController::getMessages');
    $routes->post('chat/messages', 'ChatController::sendMessage');
});

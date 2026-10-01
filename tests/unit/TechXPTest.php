<?php

namespace Tests\Unit;

use App\Libraries\VenueWeatherService;
use App\Models\AdminModel;
use App\Models\ChatMessageModel;
use App\Models\RegistrationModel;
use CodeIgniter\Test\CIUnitTestCase;

class TechXPTest extends CIUnitTestCase
{
    public function testRegistrationIdFormat(): void
    {
        $db = \Config\Database::connect('default');
        $model = new RegistrationModel($db);
        $id = $model->generateRegistrationId();

        $this->assertNotEmpty($id);
        $this->assertMatchesRegularExpression('/^TXP-2026-[0-9]{4}$/', $id);
    }

    public function testFindAttendeeByEmailAndMobile(): void
    {
        $db = \Config\Database::connect('default');
        $model = new RegistrationModel($db);

        // Test finding seeded user by email
        $byEmail = $model->findByEmailOrMobile('aarav.sharma@techcorp.in');
        $this->assertNotNull($byEmail);
        $this->assertSame('Aarav Sharma', $byEmail['full_name']);

        // Test finding the same user by mobile
        $byMobile = $model->findByEmailOrMobile('9876543210');
        $this->assertNotNull($byMobile);
        $this->assertSame($byEmail['id'], $byMobile['id']);
    }

    public function testVenueWeatherServiceIntegration(): void
    {
        $weatherService = new VenueWeatherService();
        $weather = $weatherService->getVenueWeather();

        $this->assertIsArray($weather);
        $this->assertTrue($weather['success']);
        $this->assertArrayHasKey('temperature', $weather);
        $this->assertArrayHasKey('condition', $weather);
        $this->assertArrayHasKey('city', $weather);
        $this->assertStringContainsString('Mumbai', $weather['city']);
    }

    public function testChatMessageRetrieval(): void
    {
        $db = \Config\Database::connect('default');
        $chatModel = new ChatMessageModel($db);
        $messages = $chatModel->getRecentMessages(10);

        $this->assertIsArray($messages);
        $this->assertNotEmpty($messages);
        $this->assertArrayHasKey('sender_name', $messages[0]);
        $this->assertArrayHasKey('message', $messages[0]);
    }

    public function testAdminModelLookup(): void
    {
        $db = \Config\Database::connect('default');
        $adminModel = new AdminModel($db);
        $admin = $adminModel->findByUsernameOrEmail('admin');

        $this->assertNotNull($admin);
        $this->assertSame('superadmin', $admin['role']);
        $this->assertTrue(password_verify('AdminPassword123!', $admin['password_hash']));
    }
}

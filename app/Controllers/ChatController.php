<?php

namespace App\Controllers;

use App\Models\ChatMessageModel;

class ChatController extends BaseController
{
    protected ChatMessageModel $chatModel;

    public function __construct()
    {
        $this->chatModel = new ChatMessageModel();
    }

    /**
     * Retrieve recent messages for live chat stream
     * GET /chat/messages or GET /api/chat/messages
     */
    public function getMessages()
    {
        $sinceId = $this->request->getGet('since_id');
        $sinceId = $sinceId !== null ? (int)$sinceId : null;

        $limit = (int)($this->request->getGet('limit') ?? 50);
        $limit = min(max($limit, 5), 100);

        $messages = $this->chatModel->getRecentMessages($limit, $sinceId);

        // Format dates and escape messages for safe UI rendering
        $formatted = array_map(function ($msg) {
            return [
                'id'          => (int)$msg['id'],
                'sender_name' => esc($msg['sender_name']),
                'sender_role' => esc($msg['sender_role']),
                'message'     => esc($msg['message']),
                'time'        => date('h:i A', strtotime($msg['created_at'])),
                'is_self'     => (session()->get('attendee_name') === $msg['sender_name']),
            ];
        }, $messages);

        return $this->response->setJSON([
            'success'  => true,
            'count'    => count($formatted),
            'messages' => $formatted,
        ]);
    }

    /**
     * Send a new message into the live event chat
     * POST /chat/send or POST /api/chat/messages
     */
    public function sendMessage()
    {
        $session = session();
        $isAttendee = $session->get('is_attendee_logged_in');
        $isAdmin    = $session->get('is_admin_logged_in');

        if (!$isAttendee && !$isAdmin) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Please log in to participate in the live event chat.',
            ]);
        }

        // Extract message from JSON payload or POST
        $json = $this->request->getJSON(true);
        $messageText = trim($json['message'] ?? (string)$this->request->getPost('message'));

        if (empty($messageText)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Message content cannot be empty.',
            ]);
        }

        // Limit message length
        if (mb_strlen($messageText) > 500) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Message is too long (maximum 500 characters).',
            ]);
        }

        if ($isAdmin) {
            $senderName = $session->get('admin_name') ?? 'Organizer';
            // $senderRole = 'admin';
            $regId = null;
        } else {
            $senderName = $session->get('attendee_name');
            // $senderRole = ($ticketType === 'VIP All-Access') ? 'vip' : 'attendee';
            $regId = $session->get('attendee_id');
        }

        $newMessage = [
            'registration_id' => $regId,
            'sender_name'     => $senderName,
            // 'sender_role'     => $senderRole,
            'message'         => strip_tags($messageText),
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        $insertedId = $this->chatModel->insert($newMessage);

        // Interactive Bot Response for common summit inquiries
        $this->handleBotReply($messageText);

        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'message' => 'Message posted.',
            'data'    => [
                'id'          => $insertedId,
                'sender_name' => esc($senderName),
                // 'sender_role' => esc($senderRole),
                'message'     => esc($newMessage['message']),
                'time'        => date('h:i A'),
                'is_self'     => true,
            ],
        ]);
    }

    /**
     * Interactive TechXP Concierge Bot auto-reply for common questions
     */
    private function handleBotReply(string $text): void
    {
        $lower = strtolower($text);
        $botReply = null;

        if (str_contains($lower, 'wifi')) {
            $botReply = 'TechXP Wi-Fi is available! Network: "TechXP-India-5G", Password: "Innovation2026". Enjoy high-speed access throughout the venue!';
        } elseif (str_contains($lower, 'lunch') || str_contains($lower, 'food') || str_contains($lower, 'coffee')) {
            $botReply = 'Networking Lunch is served at 1:00 PM in the Grand Dining Pavilion (Level 1). Coffee & refreshments are open all day in the Tech Lounge.';
        } elseif (str_contains($lower, 'agenda') || str_contains($lower, 'schedule')) {
            $botReply = 'Today\'s agenda: 10:00 AM Keynote, 11:30 AM AI Track, 2:00 PM Cloud Architecture Panel, 4:30 PM Startup Pitch Finals & Awards.';
        } elseif (str_contains($lower, 'certificate') || str_contains($lower, 'badge')) {
            $botReply = 'Digital Attendee Badges and Certificates of Participation can be downloaded directly from your Attendee Portal at any time!';
        }

        if ($botReply !== null) {
            $this->chatModel->insert([
                'registration_id' => null,
                'sender_name'     => 'TechXP Bot',
                'sender_role'     => 'bot',
                'message'         => $botReply,
                'created_at'      => date('Y-m-d H:i:s', time() + 1),
            ]);
        }
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table            = 'chat_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'registration_id',
        'sender_name',
        'sender_role',
        'message',
        'created_at',
    ];

    protected $useTimestamps = false; // We manage created_at explicitly

    /**
     * Retrieve recent messages for live chat room
     */
    public function getRecentMessages(int $limit = 50, ?int $sinceId = null): array
    {
        $builder = $this->orderBy('id', 'ASC');

        if ($sinceId !== null && $sinceId > 0) {
            $builder->where('id >', $sinceId);
        }

        return $builder->findAll($limit);
    }
}

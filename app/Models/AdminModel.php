<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'admins';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'full_name',
        'email',
        'password_hash',
        'role',
        'last_login',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Locate admin by Username or Email
     */
    public function findByUsernameOrEmail(string $identifier): ?array
    {
        $cleanIdentifier = trim($identifier);

        return $this->groupStart()
            ->where('username', $cleanIdentifier)
            ->orWhere('email', $cleanIdentifier)
            ->groupEnd()
            ->first();
    }

    /**
     * Record login timestamp
     */
    public function recordLogin(int $id): bool
    {
        return $this->update($id, [
            'last_login' => date('Y-m-d H:i:s'),
        ]);
    }
}

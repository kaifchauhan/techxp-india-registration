<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table            = 'registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'registration_id',
        'full_name',
        'email',
        'mobile',
        'company',
        'designation',
        'city',
        'country',
        'session_preference',
        'consent',
        'password_hash',
        'status',
        'ip_address',
        'check_in_time',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules (can be customized per scenario in controller)
    protected $validationRules = [
        'full_name'   => 'required|min_length[3]|max_length[100]',
        'email'       => 'required|valid_email|is_unique[registrations.email,id,{id}]',
        'mobile'      => 'required|regex_match[/^[0-9]{10}$/]|is_unique[registrations.mobile,id,{id}]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered',
        ],
        'mobile' => [
            'is_unique'   => 'This mobile number is already registered',
            'regex_match' => 'Please provide a valid 10-digit mobile number.',
        ],
    ];

  
    public function findByEmailOrMobile(string $identifier): ?array
    {
        $cleanIdentifier = trim($identifier);

        return $this->groupStart()
            ->where('email', $cleanIdentifier)
            ->orWhere('mobile', $cleanIdentifier)
            ->groupEnd()
            ->first();
    }

    /**
     * Generate a unique badge ID, e.g. TXP-2026-4892
     */
    public function generateRegistrationId(): string
    {
        do {
            $candidate = 'TXP-2026-' . random_int(1000, 9999);
            $exists = $this->where('registration_id', $candidate)->first();
        } while ($exists);

        return $candidate;
    }

    /**
     * Filter registrations for Admin view and REST API
     */
    public function getFilteredRegistrations(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $builder = $this->applyFilters($filters);
        return $builder->orderBy('id', 'DESC')->findAll($limit, $offset);
    }

    /**
     * Count matching filtered registrations for pagination
     */
    public function countFilteredRegistrations(array $filters = []): int
    {
        $builder = $this->applyFilters($filters);
        return $builder->countAllResults();
    }

    /**
    * Internal helper to apply registration filters
     */
    private function applyFilters(array $filters)
    {
        $builder = $this;

        if (!empty($filters['search'])) {
            $term = trim($filters['search']);
            $builder = $builder->groupStart()
                ->like('full_name', $term)
                ->orLike('email', $term)
                ->orLike('mobile', $term)
                ->orLike('company', $term)
                ->orLike('registration_id', $term)
                ->groupEnd();
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $builder = $builder->where('status', $filters['status']);
        }

        if (!empty($filters['session'])) {
            $builder = $builder->where('session_preference', $filters['session']);
        }

        if (!empty($filters['country'])) {
            $builder = $builder->where('country', $filters['country']);
        }


        return $builder;
    }

    /**
     * Compute comprehensive registration stats for Admin dashboard
     */
    public function getStats(): array
    {
        $total = $this->countAllResults();
        $confirmed = $this->where('status', 'confirmed')->countAllResults();
        $attended = $this->where('status', 'attended')->countAllResults();
        $cancelled = $this->where('status', 'cancelled')->countAllResults();

        // Recent registrations (last 5)
        $recent = $this->orderBy('created_at', 'DESC')->findAll(5);

        return [
            'total'        => $total,
            'confirmed'    => $confirmed,
            'attended'     => $attended,
            'cancelled'    => $cancelled,
            'recent'       => $recent,
        ];
    }

    /**
     * Check-in an attendee
     */
    public function checkIn(int $id): bool
    {
        return $this->update($id, [
            'status'        => 'attended',
            'check_in_time' => date('Y-m-d H:i:s'),
        ]);
    }
}

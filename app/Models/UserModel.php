<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'full_name', 'email', 'created_at', 'password'];

    public function demoUser(): ?array
    {
        // The public Profile never receives the password hash.
        return $this->select('id, username, full_name, email, created_at')
            ->orderBy('id', 'ASC')->first();
    }
    public function forLogin(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}

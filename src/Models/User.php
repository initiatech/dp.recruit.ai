<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public ?int $id = null;
    public ?int $org_id = null;
    public string $full_name;
    public string $email;
    public string $role;
    public string $status;
    public string $password_hash;
    public string $created_at;
}

<?php

declare(strict_types=1);

namespace App\Models;

class Candidate
{
    public ?int $id = null;
    public string $full_name;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $cv_text = null;
    public ?string $source = null;
    public string $created_at;
}

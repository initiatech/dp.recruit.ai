<?php

declare(strict_types=1);

namespace App\Models;

class Label
{
    public ?int $id = null;
    public ?int $org_id = null;
    public string $name;
    public string $created_at;
}

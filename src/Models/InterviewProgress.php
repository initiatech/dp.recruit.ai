<?php

declare(strict_types=1);

namespace App\Models;

class InterviewProgress
{
    public ?int $id = null;
    public string $conversation__id;
    public string $field_key;
    public ?string $field_value = null;
    public string $collected_at;
}

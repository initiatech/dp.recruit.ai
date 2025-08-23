<?php

declare(strict_types=1);

namespace App\Models;

class Conversation
{
    public string $id; // UUID
    public int $candidate_id;
    public int $job_id;
    public string $status;
    public string $started_at;
    public ?string $closed_at = null;
}

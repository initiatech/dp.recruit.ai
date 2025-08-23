<?php

declare(strict_types=1);

namespace App\Models;

class RecruiterNote
{
    public ?int $id = null;
    public string $conversation_id;
    public int $user_id;
    public string $note;
    public string $created_at;
}

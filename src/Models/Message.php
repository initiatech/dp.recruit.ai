<?php

declare(strict_types=1);

namespace App\Models;

class Message
{
    public ?int $id = null;
    public string $conversation_id;
    public string $sender;
    public ?string $content_text = null;
    public ?string $content_audio_url = null;
    public ?int $start_ms = null;
    public ?int $end_ms = null;
    public bool $barge_in = false;
    public ?float $confidence = null;
    public string $created_at;
}

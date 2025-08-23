<?php

declare(strict_types=1);

namespace App\Models;

class HeatScore
{
    public ?int $id = null;
    public string $conversation_id;
    public int $score;
    public string $breakdown_json;
    public ?string $rubric_json = null;
    public string $calculated_at;
}

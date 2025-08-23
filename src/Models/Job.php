<?php

declare(strict_types=1);

namespace App\Models;

class Job
{
    public ?int $id = null;
    public string $title;
    public ?string $description = null;
    public string $required_fields_json;
    public ?string $must_have = null;
    public ?string $nice_to_have = null;
    public ?string $block_rules = null;
    public int $heat_threshold;
    public string $created_at;

    /**
     * @return array<string, string>
     */
    public function getRequiredFields(): array
    {
        return json_decode($this->required_fields_json, true) ?? [];
    }
}

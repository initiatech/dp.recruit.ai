<?php

declare(strict_types=1);

namespace App\Models;

class AuditLog
{
    public ?int $id = null;
    public ?int $actor_user_id = null;
    public string $entity_type;
    public string $entity_id;
    public string $action;
    public ?string $before_json = null;
    public ?string $after_json = null;
    public string $created_at;
}

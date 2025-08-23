<?php

declare(strict_types=1);

namespace App\Utils;

use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * A simple wrapper around the Ramsey/Uuid library for easy mocking and replacement.
 */
class Uuid
{
    /**
     * Generate a version 4 (random) UUID.
     */
    public static function v4(): string
    {
        return RamseyUuid::uuid4()->toString();
    }
}

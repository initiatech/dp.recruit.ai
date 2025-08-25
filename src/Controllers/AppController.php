<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class AppController extends Controller
{
    public function healthz(): Response
    {
        return Response::json(['status' => 'ok', 'timestamp' => date('c')]);
    }

    public function version(): Response
    {
        return Response::json(['version' => '0.1.0-alpha']);
    }

    public function metrics(): Response
    {
        return Response::json(['memory_usage_bytes' => memory_get_usage()]);
    }

    public function config(): Response
    {
        return Response::json([
            'voice_enabled' => filter_var($_ENV['VOICE_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'ai_streaming' => filter_var($_ENV['AI_STREAMING'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'app_name' => 'Recruiter-AI',
        ]);
    }
}

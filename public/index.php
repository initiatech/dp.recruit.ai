<?php

declare(strict_types=1);

// Set the content type to JSON for all responses
header("Content-Type: application/json; charset=UTF-8");

// Autoload dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // This will happen in a CI environment where .env file might not be present
    // We can ignore this error and rely on environment variables set directly
}

// Basic router
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI
if (false !== $pos = strpos($requestUri, '?')) {
    $requestUri = substr($requestUri, 0, $pos);
}
$requestUri = rawurldecode($requestUri);

switch ($requestUri) {
    case '/healthz':
        if ($requestMethod === 'GET') {
            http_response_code(200);
            echo json_encode(['status' => 'ok', 'timestamp' => date('c')]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;

    case '/config/public':
        if ($requestMethod === 'GET') {
            http_response_code(200);
            echo json_encode([
                'voice_enabled' => filter_var($_ENV['VOICE_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
                'ai_streaming' => filter_var($_ENV['AI_STREAMING'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
                'app_name' => 'Recruiter-AI',
            ]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}

<?php

declare(strict_types=1);

// This is the single entry point to the application.

// 1. Autoload dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Load environment variables
try {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    // It's okay if .env file is not present, we can rely on server-level env vars.
}

// 3. Global Error and Exception Handling
use App\Core\Response;

set_exception_handler(function (\Throwable $exception) {
    // In a real app, you would log the full exception details.
    // error_log($exception->getMessage() . "\n" . $exception->getTraceAsString());

    // For the client, send a generic server error response.
    // In dev mode, you might want to send more details.
    $message = ($_ENV['APP_ENV'] === 'dev') ? $exception->getMessage() : 'An internal server error occurred.';
    Response::error($message, 500, 'SERVER_ERROR')->send();
});

// 4. Define and dispatch routes
use App\Core\Request;
use App\Core\Router;
use App\Controllers\AppController;
use App\Controllers\CandidatesController;
use App\Controllers\JobsController;
use App\Controllers\ConversationsController;

$router = new Router();

// == App routes ==
$router->get('/healthz', [AppController::class, 'healthz']);
$router->get('/version', [AppController::class, 'version']);
$router->get('/metrics', [AppController::class, 'metrics']);
$router->get('/config/runtime', [AppController::class, 'config']);

// == API routes ==
$router->post('/candidates', [CandidatesController::class, 'create']);
$router->post('/jobs', [JobsController::class, 'create']);
$router->post('/conversations', [ConversationsController::class, 'create']);
$router->get('/conversations/{id}', [ConversationsController::class, 'getOne']);

// 5. Create request object from globals and dispatch
$request = Request::createFromGlobals();
$router->dispatch($request);

<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function __construct(
        private mixed $data,
        private int $statusCode = 200,
        private array $headers = []
    ) {
    }

    public function send(): void
    {
        // Remove any existing headers
        header_remove();

        // Set new headers
        http_response_code($this->statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Send body
        if ($this->data !== null) {
            echo json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        // Terminate script to prevent further output
        exit();
    }

    public static function json(mixed $data, int $statusCode = 200): self
    {
        return new self($data, $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, string $errorCode = null): self
    {
        $payload = [
            'error' => [
                'code' => $errorCode ?? "E{$statusCode}",
                'message' => $message,
            ]
        ];
        return new self($payload, $statusCode);
    }
}

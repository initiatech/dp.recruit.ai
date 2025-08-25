<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    private array $params = [];
    private mixed $body = null;

    public function __construct(
        public readonly string $uri,
        public readonly string $method
    ) {
        $this->parseBody();
    }

    public static function createFromGlobals(): self
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        return new self($uri, $_SERVER['REQUEST_METHOD']);
    }

    private function parseBody(): void
    {
        if ($this->method === 'GET') {
            return;
        }

        if (!empty($_POST)) {
            $this->body = $_POST;
            return;
        }

        $jsonBody = file_get_contents('php://input');
        if (!empty($jsonBody)) {
            $this->body = json_decode($jsonBody, true);
        }
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function setRouteParams(array $params): void
    {
        $this->params = $params;
    }

    public function getRouteParam(string $name): ?string
    {
        return $this->params[$name] ?? null;
    }
}

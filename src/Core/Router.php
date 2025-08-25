<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Request;
use App\Core\Response;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, array $action): self
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
        ];
        return $this;
    }

    public function get(string $uri, array $action): self
    {
        return $this->add('GET', $uri, $action);
    }

    public function post(string $uri, array $action): self
    {
        return $this->add('POST', $uri, $action);
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            // Convert route URI to a regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $request->method && preg_match($pattern, $request->uri, $matches)) {
                // Remove full match and numeric keys
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setRouteParams($params);

                [$controllerClass, $method] = $route['action'];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $method)) {
                        // Call the controller method
                        $response = $controller->$method($request);
                        if ($response instanceof Response) {
                            $response->send();
                        }
                        return;
                    }
                }
            }
        }

        // No route matched
        Response::error('Not Found', 404)->send();
    }
}

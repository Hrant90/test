<?php

namespace Api;

class Route {
    private array $routes = [];

    public function __construct(
        private readonly string $requestUri,
        private readonly string $requestMethod,
    ) {}

    public function get(string $path, callable|array $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    public function dispatch(): void {
        $requestUri = parse_url($this->requestUri, PHP_URL_PATH);
        parse_str(parse_url($this->requestUri, PHP_URL_QUERY) ?? '', $queryParams); // Parse query params

        foreach ($this->routes[$this->requestMethod] as $path => $callback) {
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $path);
            $pattern = "#^" . rtrim($pattern, '/') . "$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $this->handleCallback($callback, $matches, $queryParams);
                return;
            }
        }

        $this->notFound();
    }

    private function handleCallback(callable|array $callback, array $params = [], array $queryParams = []): void {
        global $container;

        if (is_callable($callback)) {
            $callback(...array_merge($params, $queryParams)); // Merge params and query
        } elseif (is_array($callback)) {
            [$controller, $method] = $callback;
            $params = [...$params, $queryParams];
            ($container->get($controller))->$method(...$params); // Merge params and query
        }
    }

    private function notFound(): void {
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}

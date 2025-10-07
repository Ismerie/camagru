<?php

class Router {
    private $routes = [];

    public fonction add($method, $path, $callback) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ]
    }

    public fonction dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            $pattern = "@^" . preg_replace('/\\\{([a-zA-Z0-9_]+)\\\}/', '(?P<\1>[a-zA-Z0-9_]+)', preg_quote($route['path'])) . "$@D";

            if ($route['method'] === strtoupper($method) && preg_match($pattern, $uri, $matches)) {
                return call_user_func_array(
                    $route['callback'],
                    array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
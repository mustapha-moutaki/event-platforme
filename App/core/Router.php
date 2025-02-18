<?php

namespace App\core;

require_once __DIR__ . '/../../vendor/autoload.php';

class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];

    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/'); // Remove trailing slashes
        $requestMethod = $_SERVER['REQUEST_METHOD'];


        foreach ($this->routes as $route) {
            $routePath = $route['path'];
            $routeMethod = $route['method'];
    
            // Convert the route path to a regex
            $regex = $this->convertToRegex($routePath);
    
            // echo "Route Path: " . $routePath . "<br>";
            // echo "Generated Regex: " . $regex . "<br>";
    
            // Check if the request URI matches the route's regex and method
            if (preg_match($regex, $requestUri, $matches) && $routeMethod === $requestMethod) {
                array_shift($matches); // Remove the full match from the array
                $controllerName = $route['controller'];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $route['action'])) {
                        return call_user_func_array([$controller, $route['action']], $matches);
                    }
                }

            }
        }

        // If no route matches, return a 404 error
        http_response_code(404);
        echo "Erreur 404 - Page non trouvée";
    }

    private function convertToRegex($path) {
        // Escape forward slashes and convert dynamic parameters to regex
        $path = preg_quote($path, '/');
        $path = str_replace(['\\{id\\}','\\{eventId\\}', '\\{regionId\\}'], ['([a-zA-Z0-9-_]+)', '([0-9]+)','([0-9]+)'], $path);
        return "/^$path$/";
    }

}
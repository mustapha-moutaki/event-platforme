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
        // Get the request URI and remove any trailing slashes
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // $requestUri = rtrim($requestUri, '/'); // Remove trailing slashes
        $requestMethod = $_SERVER['REQUEST_METHOD'];
    
        // echo "Request URI: " . $requestUri . "<br>";
        // echo "Request Method: " . $requestMethod . "<br>";
    
        foreach ($this->routes as $route) {
            $routePath = $route['path'];
            $routeMethod = $route['method'];

            // Check for dynamic parameters
            if (preg_match($this->convertToRegex($routePath), $requestUri, $matches) && $routeMethod === $requestMethod) {
    
            // Convert the route path to a regex
            // $regex = $this->convertToRegex($routePath);
    
            // // echo "Route Path: " . $routePath . "<br>";
            // // echo "Generated Regex: " . $regex . "<br>";
    
            // // Check if the request URI matches the route's regex and method
            // if (preg_match($regex, $requestUri, $matches) && $routeMethod === $requestMethod) {
                array_shift($matches); // Remove the full match from the array
    
    
                // Check if the controller exists
                $controllerName = $route['controller'];
                // echo "Controller Name: " . $controllerName . "<br>";
    
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $route['action'])) {
                        return call_user_func_array([$controller, $route['action']], $matches);
                    }
                } else {
                    echo "Controller doesn't exist!<br>";
                }

            }
        }
    
        // If no route matches, return a 404 error
        http_response_code(404);
        // echo "No matching route for URI: " . $requestUri . " with method: " . $requestMethod;
        echo "Erreur 404 - Page non trouvée";
    }
    
    
    
    
    
    private function convertToRegex($path) {
        // Escape forward slashes and convert dynamic parameters to regex
        $path = preg_quote($path, '/');
        $path = str_replace(['\\{id\\}','\\{eventId\\}', '\\{regionId\\}'], ['([a-zA-Z0-9-_]+)', '([0-9]+)','([0-9]+)'], $path);
        return "/^$path$/";
    }
    
}

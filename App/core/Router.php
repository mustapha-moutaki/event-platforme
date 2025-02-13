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
        $requestMethod = $_SERVER['REQUEST_METHOD'];
    
        // echo "<pre>";
        // var_dump($this->routes);
        // echo "</pre>";
        // echo "Requested URI: " . $requestUri . "<br>"; 
        
        foreach ($this->routes as $route) {
            // Normalize the request URI
            $routePath = $route['path'];
            $routeMethod = $route['method'];
    
            // Check for dynamic parameters
            if (preg_match($this->convertToRegex($routePath), $requestUri, $matches) && $routeMethod === $requestMethod) {
                // Handle dynamic parameters if needed
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
        $path = str_replace(['\\{id\\}', '\\{slug\\}'], ['([0-9]+)', '([a-zA-Z0-9-]+)'], $path);
        return "/^$path$/";
    }
    
}

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
            // echo "Route Path: " . $route['path'] . "<br>"; 
            if ($route['path'] === $requestUri && $route['method'] === $requestMethod) {
                // $controllerName = 'App\\Controllers\\' . $route['controller'];
                $controllerName =  $route['controller'];

            
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $route['action'])) {
                        return call_user_func([$controller, $route['action']]);
                        
                    }
                }
            }
        }
    
        // Si aucune route ne correspond, afficher une erreur 404
        http_response_code(404);
        echo "Erreur 404 - Page non trouvée";
    }
    
}

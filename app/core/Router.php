<?php

namespace App\Core;

/**
 * Simple Router class
 */
class Router
{
    private $routes = [];
    
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if running in subdirectory
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/' && strpos($requestPath, $basePath) === 0) {
            $requestPath = substr($requestPath, strlen($basePath));
        }
        $requestPath = $requestPath ?: '/';
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = $this->convertToRegex($route['path']);
                if (preg_match($pattern, $requestPath, $matches)) {
                    array_shift($matches); // Remove full match
                    $this->callHandler($route['handler'], $matches);
                    return;
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        $this->render404();
    }
    
    private function convertToRegex($path)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    private function callHandler($handler, $params = [])
    {
        list($controllerName, $method) = explode('@', $handler);
        $controllerClass = "App\\Controllers\\" . $controllerName;
        
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
                return;
            }
        }
        
        // Controller or method not found
        http_response_code(500);
        echo "Controller or method not found: " . $handler;
    }
    
    private function render404()
    {
        include APP_PATH . '/views/errors/404.php';
    }
}

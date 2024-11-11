<?php

namespace Vendor\Routes;

use Config\Config;

class Router {
    private $routes = [];

    public function Route($method, $path, $action) {
        $this->addRoute(strtoupper($method), $path, null, $action);
    }
    public function collectRoutes() {

        
    }

    // start acting for routes..
    public function capture() {
        $method = $_SERVER["REQUEST_METHOD"];
        $uri = $_SERVER["REQUEST_URI"];
        $this->dispatch($method, $uri);
    }
    // Register a route with a specific HTTP method
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),  // Normalize method to uppercase
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    // Match the route and call the appropriate controller method
    public function dispatch($method, $uri) {
        $found = false;
        foreach ($this->routes as $route) {
            if ($route['method'] == strtoupper($method) && $route['path'] == $uri) {
                // Create an instance of the controller and call the action method
                $result = null;
                if($route['controller'] != null) {
                    $controller = new $route['controller']();
                    $result =call_user_func([$controller, $route['action']]);
                } else {
                    $result =call_user_func($route['action']);
                }
                echo $result;
                $found = true;
                break;
            } 
        }

        if(!$found) {
            if(file_exists(Config::instance()->PublicPath."$uri")) {
                echo file_get_contents(Config::instance()->PublicPath."/$uri");
            } else {
                //echo "404 Not Found! Not found : $method($uri)";
                echo "404 Not Found! Not found : $method($uri)\n"
                ."File path correction :".Config::instance()->PublicPath."$uri"
                ."\n"
                .json_encode($this->routes, JSON_PRETTY_PRINT);
            }
            return;
        }
    }
}
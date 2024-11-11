<?php
namespace App\Routes;

use Vendor\Routes\Router;
use Vendor\Routes\AnnotationParser;


class BaseRouter extends Router
{
    public function   collectRoutes()
    {
        $this->addWelcomeRoutes();
        $this->addUserRoutes();

        $this->capture();
    }

    private function addWelcomeRoutes() {
        $routes = AnnotationParser::parse('App\Controllers\UserController');
        //var_dump($routes);
        foreach ($routes as $route) {
            $this->addRoute($route['method'], $route['path'], $route['controller'], $route['action']);
        }
    }

    private function addUserRoutes() {
        $routes = AnnotationParser::parse('App\Controllers\WelcomeController');
        //var_dump($routes);
        foreach ($routes as $route) {
            $this->addRoute($route['method'], $route['path'], $route['controller'], $route['action']);
        }
    }
}
<?php

namespace Vendor\Routes;

class AnnotationParser {

    // Parse annotations to generate routes
    public static function parse($controller)
{
    $reflection = new \ReflectionClass($controller);
    $routes = [];

    // Loop through methods and extract annotations
    foreach ($reflection->getMethods() as $method) {
        $docComment = $method->getDocComment();

        // Match the @route annotation with HTTP method and path
        preg_match_all('/@route\s*\(\s*(\w+)\s*,\s*(\'[^\']+\')\s*\)/', $docComment, $matches);
        
        // If matches are found, process each route
        if (!empty($matches[1]) && !empty($matches[2])) {
            foreach ($matches[1] as $index => $httpMethod) {
                $httpMethod = strtoupper(trim($httpMethod)); // Ensure it's uppercase
                $routePath = trim($matches[2][$index], "'"); // Remove surrounding quotes

                // Register the route with the correct HTTP method
                $routes[] = [
                    'method' => $httpMethod,
                    'path' => $routePath,
                    'controller' => $controller,
                    'action' => $method->getName()
                ];
            }
        }
    }

    return $routes;
}
}
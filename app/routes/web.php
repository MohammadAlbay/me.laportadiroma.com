<?php
use App\Routes\BaseRouter;

$router = new BaseRouter();

$router->Route('get', '/sayHI', function() {
    return "Hi La Porta Di Roma";
});

$router->Route('get', '/version', function() {
    return "Version 0.0.1 [pre BETA]";
});


$router->collectRoutes();

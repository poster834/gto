<?php
require __DIR__ . '/vendor/autoload.php';

try{
    $routes = require __DIR__ . '/../gto/src/routes.php';

    $route = $_GET['route'] ?? '';
    $isRouteFound = false;

    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);  
        
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }  
    }

    if (!$isRouteFound) {
        throw new \Exception();
    }

    unset($matches[0]);
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller -> $actionName(...$matches);
} catch (\Exception $e) {
      var_dump($e);
}
<?php

require __DIR__ . '/vendor/autoload.php';

try{
    $routes = require __DIR__ . '/../gtm/src/routes.php';

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
        throw new \Gtm\Exceptions\NotFoundException();
    }

    unset($matches[0]);
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller -> $actionName(...$matches);
} catch (\Gtm\Exceptions\NotFoundException $e) {
    $view = new \Gtm\View\View(__DIR__. '/src/templates/errors/');
    $view->renderHtml('404.php', ['error'=>$e->getMessage()],404);
} catch (\Gtm\Exceptions\InvalidArgumentException $e){

    
} catch (\Gtm\Exceptions\NotAllowException $e) {
    $view = new \Gtm\View\View(__DIR__. '/src/templates/errors/');
    $view->renderHtml('deny.php', ['error'=>$e->getMessage()],404);
} catch (\Exception $e){
    echo $e->getMessage();
}
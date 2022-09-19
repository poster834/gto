<?php
return [
    '~^$~' => [Gtm\Controllers\MainController::class, 'main'],
    '~^admin$~' => [Gtm\Controllers\AdminController::class, 'mainAdmin'],
    '~^admin/company/$~' => [Gtm\Controllers\AdminController::class, 'company'],
    '~^admin/roles/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'roles'],
    '~^system$~' => [Gtm\Controllers\MainController::class, 'system'],
    '~^auth$~' => [Gtm\Controllers\UsersController::class, 'login'],
    '~^logout$~' => [Gtm\Controllers\UsersController::class, 'logout'],
    '~^roles/editRow/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'editRow'],
    '~^roles/edit/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'edit'],
    '~^roles/delete/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'delete'],
    '~^roles/save$~' => [Gtm\Controllers\RolesController::class, 'saveRole'],
    
];
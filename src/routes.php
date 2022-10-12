<?php
use Gtm\Models\Users\UsersAuthService;
$loginUser = UsersAuthService::getUserByToken();

$basicRoutes = [
        '~^$~' => [Gtm\Controllers\MainController::class, 'main'],
        '~^auth$~' => [Gtm\Controllers\UsersController::class, 'login'],
        '~^logout$~' => [Gtm\Controllers\UsersController::class, 'logout'],
];

$userRoutes = [
    '~^system$~' => [Gtm\Controllers\MainController::class, 'system'],
];
$userRoutes = array_merge($basicRoutes, $userRoutes);

$adminRoutes = [
        '~^admin$~' => [Gtm\Controllers\AdminController::class, 'mainAdmin'],
        '~^admin/company/$~' => [Gtm\Controllers\AdminController::class, 'company'],
        '~^admin/roles/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'roles'],
        '~^admin/users/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'users'],
        '~^admin/directions/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'directions'],
        '~^admin/regions/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'regions'],
        '~^admin/failuresTypes/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'failuresTypes'],
        '~^admin/offensesTypes/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'offensesTypes'],
        '~^admin/machines/$~' => [Gtm\Controllers\AdminController::class, 'machines'],

        '~^machines/block/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'block'],
        // '~^machines/edit/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'edit'],
        // '~^machines/delete/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'delete'],
        // '~^machines/save$~' => [Gtm\Controllers\MachinesController::class, 'saveMachine'],
        // '~^addMachine$~' => [Gtm\Controllers\MachinesController::class, 'showAdd'],
        '~^admin/machines_groups/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'groupsUnion'],
        '~^admin/machines_owners/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'ownersUnion'],
        '~^admin/machines_gpsFailures/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'gpsFailuresUnion'],
        '~^admin/machines_offenses/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'offensesUnion'],

        '~^offensesTypes/editRow/(\d+)$~' => [Gtm\Controllers\OffensesTypesController::class, 'editRow'],
        '~^offensesTypes/edit/(\d+)$~' => [Gtm\Controllers\OffensesTypesController::class, 'edit'],
        '~^offensesTypes/delete/(\d+)$~' => [Gtm\Controllers\OffensesTypesController::class, 'delete'],
        '~^offensesTypes/save$~' => [Gtm\Controllers\OffensesTypesController::class, 'saveOffensesType'],
        '~^addOffensesType$~' => [Gtm\Controllers\OffensesTypesController::class, 'showAdd'],

        '~^failuresTypes/editRow/(\d+)$~' => [Gtm\Controllers\FailuresTypesController::class, 'editRow'],
        '~^failuresTypes/edit/(\d+)$~' => [Gtm\Controllers\FailuresTypesController::class, 'edit'],
        '~^failuresTypes/delete/(\d+)$~' => [Gtm\Controllers\FailuresTypesController::class, 'delete'],
        '~^failuresTypes/save$~' => [Gtm\Controllers\FailuresTypesController::class, 'saveFailuresType'],
        '~^addFailuresType$~' => [Gtm\Controllers\FailuresTypesController::class, 'showAdd'],

        '~^directions/editRow/(\d+)$~' => [Gtm\Controllers\DirectionsController::class, 'editRow'],
        '~^directions/edit/(\d+)$~' => [Gtm\Controllers\DirectionsController::class, 'edit'],
        '~^directions/delete/(\d+)$~' => [Gtm\Controllers\DirectionsController::class, 'delete'],
        '~^directions/save$~' => [Gtm\Controllers\DirectionsController::class, 'saveDirection'],
        '~^addDirection$~' => [Gtm\Controllers\DirectionsController::class, 'showAdd'],
        
        '~^roles/editRow/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'editRow'],
        '~^roles/edit/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'edit'],
        '~^roles/delete/(\d+)$~' => [Gtm\Controllers\RolesController::class, 'delete'],
        '~^roles/save$~' => [Gtm\Controllers\RolesController::class, 'saveRole'],
        '~^addRole$~' => [Gtm\Controllers\RolesController::class, 'showAdd'],

        '~^regions/editRow/(\d+)$~' => [Gtm\Controllers\RegionsController::class, 'editRow'],
        '~^regions/edit/(\d+)$~' => [Gtm\Controllers\RegionsController::class, 'edit'],
        '~^regions/delete/(\d+)$~' => [Gtm\Controllers\RegionsController::class, 'delete'],
        '~^regions/save$~' => [Gtm\Controllers\RegionsController::class, 'saveRegion'],
        '~^addRegion$~' => [Gtm\Controllers\RegionsController::class, 'showAdd'],

        '~^users/editRow/(\d+)$~' => [Gtm\Controllers\UsersController::class, 'editRow'],
        '~^users/edit/(\d+)$~' => [Gtm\Controllers\UsersController::class, 'edit'],
        '~^users/delete/(\d+)$~' => [Gtm\Controllers\UsersController::class, 'delete'],
        '~^users/changePassword/(\d+)$~' => [Gtm\Controllers\UsersController::class, 'changePassword'],        
        '~^users/save$~' => [Gtm\Controllers\UsersController::class, 'saveUser'],
        '~^users/block/(\d+)$~' => [Gtm\Controllers\UsersController::class, 'changeBlocking'],
        '~^addUser$~' => [Gtm\Controllers\UsersController::class, 'showAdd'],
        '~^company/save$~' => [Gtm\Controllers\AdminController::class, 'saveCompany'],
        '~^logo/load$~' => [Gtm\Controllers\AdminController::class, 'logoLoad'],
        '~^schema/check$~' => [Gtm\Controllers\AdminController::class, 'schemaCheck'],
        // '~^schema/load$~' => [Gtm\Controllers\AdminController::class, 'schemaLoad'],

];
$adminRoutes = array_merge($adminRoutes, $userRoutes);


if (isset($loginUser) && $loginUser->isAdmin()) {
    return $adminRoutes;
} elseif (isset($loginUser)) {
   return $userRoutes;
} else {
    return $basicRoutes;
}
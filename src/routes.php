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
    '~^indexPage/$~' => [Gtm\Controllers\MainController::class, 'index'],
    '~^schemaPage/$~' => [Gtm\Controllers\MainController::class, 'schema'],
    '~^directionsPage/$~' => [Gtm\Controllers\MainController::class, 'directions'],
    '~^failuresPageByDirection/$~' => [Gtm\Controllers\MainController::class, 'failuresPageByDirection'],
    '~^failuresPageByType/$~' => [Gtm\Controllers\MainController::class, 'failuresPageByType'],
    '~^failuresPageByMechanic/$~' => [Gtm\Controllers\MainController::class, 'failuresPageByMechanic'],

    '~^getMachineInfo/(.+)$~' => [Gtm\Controllers\MachinesController::class, 'getMachineInfo'],
    '~^system/showFailures/(.+)/(\d+)$~' => [Gtm\Controllers\FailuresController::class, 'show'],
    '~^system/saveTask/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'saveTask'],
    '~^system/deleteTask/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'deleteTask'],
    '~^system/newFailuresPhoto/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'addPhoto'],
    '~^system/failures/showPhoto/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'showPhoto'],
    '~^system/failuresPhoto/delete/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'deletePhoto'],
    '~^system/addFailures/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'showAddFailuresBtn'],    
    '~^system/showAddFailureForm/(.+)/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'showAddFailuresForm'], 
    '~^system/changeMechanic/(.+)/(.+)$~' => [Gtm\Controllers\MachinesController::class, 'changeMechanic'], 
    '~^system/changeRegion/(.+)/(.+)$~' => [Gtm\Controllers\MachinesController::class, 'changeRegion'], 
    '~^system/saveFailure/(.+)/(.+)/(.+)/(.+)/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'saveFailure'], 
    '~^system/selectDirection/(.+)$~' => [Gtm\Controllers\DirectionsController::class, 'selectDirection'], 
    '~^system/selectFailuresType/(.+)/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'selectFailuresType'], 
    '~^system/showRegionInfo/(.+)/(.+)$~' => [Gtm\Controllers\RegionsController::class, 'showRegionInfo'],
    '~^system/sendFailuresAlerts/(.+)$~' => [Gtm\Controllers\AlertsController::class, 'sendFailuresAlerts'],
    '~^system/inParking/(.+)$~' => [Gtm\Controllers\ParkingsController::class, 'inParking'],
    '~^system/outParking/(.+)$~' => [Gtm\Controllers\ParkingsController::class, 'outParking'],
    '~^system/saveDescription/(.+)/(.+)$~' => [Gtm\Controllers\FailuresController::class, 'saveDescription'],
    '~^system/setParkingReason/(.+)/(.+)$~' => [Gtm\Controllers\ParkingsController::class, 'setParkingReason'],  
    '~^system/setParkingComment/(.+)/(.+)$~' => [Gtm\Controllers\ParkingsController::class, 'setParkingComment'],  
    '~^wialonAccounts/(\d+)$~' => [Gtm\Controllers\WialonAccountController::class, 'wialonAccounts'],
    '~^wialonAccounts/$~' => [Gtm\Controllers\WialonAccountController::class, 'wialonAccounts'],
    '~^saveToken/(\d+)/(.+)$~' => [Gtm\Controllers\WialonAccountController::class, 'saveToken'],
    '~^updateMachineList/(\d+)/(.+)$~' => [Gtm\Controllers\WialonAccountController::class, 'updateMachineList'],
    '~^hiredCar/(\d+)$~' => [Gtm\Controllers\HiredMachineController::class, 'showHiredCar'],
    '~^showHCarInfo/(\d+)$~' => [Gtm\Controllers\HiredMachineController::class, 'showHCarInfo'],
    '~^addOffensesHiredCar/(.+)/(\d+)/(\d+)$~' => [Gtm\Controllers\HiredMachineController::class, 'addOffensesHiredCar'],
    '~^getStops/(\d+)/(.+)/(.+)/(.+)/(.+)/(.+)$~' => [Gtm\Controllers\MachinesController::class, 'getStops'],
    
    

];
$userRoutes = array_merge($basicRoutes, $userRoutes);

$adminRoutes = [
        '~^admin$~' => [Gtm\Controllers\AdminController::class, 'mainAdmin'],
        '~^getAgToken/$~' => [Gtm\Controllers\AdminController::class, 'getAgToken'],
        '~^company/$~' => [Gtm\Controllers\AdminController::class, 'company'],
        '~^roles/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'roles'],
        '~^users/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'users'],
        '~^directions/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'directions'],
        '~^regions/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'regions'],
        '~^failuresTypes/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'failuresTypes'],
        '~^offensesTypes/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'offensesTypes'],
        '~^machines/$~' => [Gtm\Controllers\AdminController::class, 'machines'],
        '~^schema/$~' => [Gtm\Controllers\AdminController::class, 'schema'],
        '~^propertiesTypes/(\d+)$~' => [Gtm\Controllers\AdminController::class, 'propertiesTypes'],
        '~^company/deleteLogo$~' => [Gtm\Controllers\AdminController::class, 'deleteLogo'],
        '~^selectGroup/(.+)/(.+)$~' => [Gtm\Controllers\GroupsController::class, 'selectGroup'],
        '~^updateGeoCoords/$~' => [Gtm\Controllers\SchemasController::class, 'updateGeoCoords'],
        
        '~^wialonAccounts/save$~' => [Gtm\Controllers\WialonAccountController::class, 'saveWialonAccount'],
        '~^addWialonAccount$~' => [Gtm\Controllers\WialonAccountController::class, 'showAddForm'],
        '~^geoSchema/$~' => [Gtm\Controllers\SchemasController::class, 'geoSchema'],

        // '~^machines/block/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'block'],
        // '~^machines/edit/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'edit'],
        // '~^machines/delete/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'delete'],
        // '~^machines/save$~' => [Gtm\Controllers\MachinesController::class, 'saveMachine'],
       
        // '~^admin/machines_groups/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'groupsUnion'],
        // '~^admin/machines_owners/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'ownersUnion'],
        // '~^admin/machines_gpsFailures/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'gpsFailuresUnion'],
        // '~^admin/machines_offenses/(\d+)$~' => [Gtm\Controllers\MachinesController::class, 'offensesUnion'],

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

        '~^propertiesTypes/editRow/(\d+)$~' => [Gtm\Controllers\PropertiesTypesController::class, 'editRow'],
        '~^propertiesTypes/edit/(\d+)$~' => [Gtm\Controllers\PropertiesTypesController::class, 'edit'],
        '~^propertiesTypes/delete/(\d+)$~' => [Gtm\Controllers\PropertiesTypesController::class, 'delete'],
        '~^propertiesTypes/save$~' => [Gtm\Controllers\PropertiesTypesController::class, 'savePropertiesTypes'],
        '~^addPropertiesTypes/(\d+)$~' => [Gtm\Controllers\PropertiesTypesController::class, 'showAdd'],
        '~^addPropertiesTypes/setUnuse/(\d+)$~' => [Gtm\Controllers\PropertiesTypesController::class, 'setUnuse'],
        
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
        '~^schema/check/(file|web|web_geo)/(machine|geo)$~' => [Gtm\Controllers\SchemasController::class, 'schemaCheck'],
        '~^schema/web/load/(.+)$~' => [Gtm\Controllers\SchemasController::class, 'schemaWebLoad'],
        '~^geoSchema/web/load/(.+)$~' => [Gtm\Controllers\SchemasController::class, 'geoSchemaWebLoad'],
        '~^schema/load$~' => [Gtm\Controllers\SchemasController::class, 'schemaLoad'],
        '~^geo_schema/load$~' => [Gtm\Controllers\SchemasController::class, 'geoSchemaLoad'],
        '~^schema/updateTableFromFile$~' => [Gtm\Controllers\SchemasController::class, 'updateTableFromFile'],
        '~^geo_schema/updateTableFromFile$~' => [Gtm\Controllers\SchemasController::class, 'updateFencesTableFromFile'],
        '~^activateGroup/(\d+)/(.+)~' => [Gtm\Controllers\GroupsController::class, 'activateGroup'],
        
        // '~^activateGroup/(\d+)/(.+)~' => [Gtm\Controllers\GroupsController::class, 'activateGroup'],

];
$adminRoutes = array_merge($adminRoutes, $userRoutes);


if (isset($loginUser) && $loginUser->isAdmin()) {
    return $adminRoutes;
} elseif (isset($loginUser)) {
   return $userRoutes;
} else {
    return $basicRoutes;
}
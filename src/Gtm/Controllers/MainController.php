<?php
namespace Gtm\Controllers;

use Gtm\Models\Users\User;
use Gtm\Models\Companys\Company;
use Gtm\Models\Devices\Device;
use Gtm\Models\Directions\Direction;
use Gtm\Models\Failures\Failure;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\Groups\Group;
use Gtm\Models\Schemas\Schema;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Regions\Region;

ini_set('display_errors',1);
error_reporting(E_ALL);

class MainController extends AbstractController
{
    public function main()
    {        
        if (isset($this->user) && $this->user->isAdmin()) {
            $this->admin();
        } else {
            $this->system();
        }
    }

    public function admin()
    {
        $this->view->renderHtml('admin/indexAdmin.php',[

        ]);
    }

    public function system()
    {
        $this->view->renderHtml('main/main.php',[
 
        ]); 
    }

    public function schema()
    {
        $groupsCount = Group::getCountInTable();
        $groupsTree = Schema::getSchemaTree();
        $machinesCount = Machine::getCountInTable();
        $dateTime = new \DateTime(Company::getById(1)->getDateUpdate());
        $devicesCount = Device::getCountInTable();
        $this->view->renderHtml('main/schema.php',[
            'groupsCount' =>$groupsCount,
            'machinesCount'=>$machinesCount,
            'devicesCount'=>$devicesCount,
            'dateTime'=>$dateTime,
            'groupsTree'=>$groupsTree,
        ]);
    }
    

    public function directions()
    {
        $this->view->renderHtml('main/directions.php',[

        ]); 
    }

    public function failuresPageByDirection()
    {
        $activeFaillures = [];
        $directions = Direction::findAll();
        $regions = Region::findAll();
        $serviceUsers = User::findAllByColumn('role_id', 2);
        $activeFailuresInDirection = Failure::getActiveFailuresInDirections();
        foreach($regions as $region)
        {
            $count = count(Failure::findAllByColumn('region_id', $region->getId()));
            if ($count>0) {
                $activeFaillures[$region->getId()]['name'] = $region->getName();
                $activeFaillures[$region->getId()]['count'] = $count;    
            }   
        }

        $this->view->renderHtml('main/failuresPageByDirection.php',[
            'directions'=>$directions,
            'regions'=>$regions,
            'serviceUsers'=>$serviceUsers,
            'activeFaillures'=>$activeFaillures,
            'activeFailuresInDirection'=>$activeFailuresInDirection,
        ]); 

    }

    public function failuresPageByType()
    {
        $failureTypes = FailuresType::findAll();
        $activeFailuresAllTypes = Failure::activeFailuresAllTypes();
        $this->view->renderHtml('main/failuresPageByType.php',[
            'failureTypes'=>$failureTypes,
            'activeFailuresAllTypes'=>$activeFailuresAllTypes,
            
        ]); 

    }

    public function failuresPageByMechanic()
    {


    }

    public function index()
    {
        $PM_firstDay = date("Y-m-1", strtotime('-1 month'));
        $PM_lastDay = date("Y-m-t", strtotime('-1 month'));
        $TM_firstDay = date('Y-m-1');
        $TM_lastDay = date('Y-m-t', time());
        $earlyP_lastDay = date("Y-m-t", strtotime('-2 month'));

        $groupsCount = Group::getCountInTable();
        $actionGroupCount = Group::getCountActionGroup();
        $machinesCount = Machine::getCountInTable();
        $actionMachineCount = count(Machine::getCountActionMachine());
        $devicesCount = Device::getCountInTable();
        $actionDeviceCount = count(Device::getCountActionDevice());
        $dateTime = new \DateTime(Company::getById(1)->getDateUpdate());
        $TMperiodActiveFailureByTypes = Failure::getActivePeriodFailureByType($TM_firstDay, $TM_lastDay);
        $TMperiodDoneFailureByTypes = Failure::getDonePeriodFailureByType($TM_firstDay, $TM_lastDay);
        $PMperiodActiveFailureByTypes = Failure::getActivePeriodFailureByType($PM_firstDay, $PM_lastDay);
        $PMperiodDoneFailureByTypes = Failure::getDonePeriodFailureByType($PM_firstDay, $PM_lastDay);
        $allFailuresCount = count(Failure::findAll());
        $allDoneFailuresCount = count(Failure::getDonePeriodFailureByType('1970-01-1', $TM_lastDay));

        $earlyPeriodActiveByTypes = Failure::getActivePeriodFailureByType('1970-01-1', $earlyP_lastDay);
        $earlyPeriodDoneByTypes = Failure::getDonePeriodFailureByType('1970-01-1', $earlyP_lastDay);
        
        $this->view->renderHtml('main/indexMain.php',[
            'groupsCount' =>$groupsCount,
            'actionGroupCount' =>$actionGroupCount,
            'machinesCount'=>$machinesCount,
            'actionMachineCount'=>$actionMachineCount,
            'devicesCount'=>$devicesCount,
            'actionDeviceCount'=>$actionDeviceCount,
            'dateTime'=>$dateTime,
            'TMperiodActiveFailureByTypes'=>$TMperiodActiveFailureByTypes,
            'TMperiodDoneFailureByTypes'=>$TMperiodDoneFailureByTypes,
            'allDoneFailuresCount'=>$allDoneFailuresCount,
            'allFailuresCount'=>$allFailuresCount,
            'PMperiodActiveFailureByTypes'=>$PMperiodActiveFailureByTypes,
            'PMperiodDoneFailureByTypes'=>$PMperiodDoneFailureByTypes,
            'earlyPeriodActiveByTypes'=>$earlyPeriodActiveByTypes,
            'earlyPeriodDoneByTypes'=>$earlyPeriodDoneByTypes,

        ]);
        
    }

    // public function getOldRecord()
    // {
    //     $host = 'localhost'; // адрес сервера 
    //     $database = 'base_sc'; // имя базы данных
    //     $user = 'operator'; // имя пользователя
    //     $password = 'operatorp@ss'; // пароль
    //     $link = mysqli_connect($host, $user, $password, $database);// Соединямся с БД
    //     $query = mysqli_query($link, "SELECT * FROM G__breakdown WHERE status_id = 3 && service_id <> 6");

    //     while ($row = mysqli_fetch_assoc($query)) {
    //        $failure = new Failure();
    //        $tid = $user_id = 0;
    //        if ($row['service_id']=='1') {$tid = 3;}
    //        if ($row['service_id']=='2') {$tid = 1;}
    //        if ($row['service_id']=='3') {$tid = 5;}
    //        if ($row['service_id']=='4') {$tid = 4;}
    //        if ($row['service_id']=='5') {$tid = 2;}
    //        if ($row['service_id']=='7') {$tid = 7;}
    //        if ($row['user_id']=='47' || $row['user_id']=='87' || $row['user_id']=='93') {$user_id = 7;}
    //        if ($row['user_id']=='88') {$user_id = 3;}
    //        if ($row['user_id']=='97') {$user_id = 2;}

    //        if ($row['mechanic_id']=='3') {$mechanic_id = 5;}
    //        if ($row['mechanic_id']=='7') {$mechanic_id = 6;}
    //        if ($row['mechanic_id']=='19') {$mechanic_id = 7;}
    //        if ($row['mechanic_id']=='6') {$mechanic_id = 8;}
    //        if ($row['mechanic_id']=='8') {$mechanic_id = 8;}
    //        if ($row['mechanic_id']=='23') {$mechanic_id = 9;}
    //        if ($row['mechanic_id']=='18') {$mechanic_id = 10;}
    //        if ($row['mechanic_id']=='1') {$mechanic_id = 10;}
    //        if ($row['mechanic_id']=='17') {$mechanic_id = 11;}
    //        if ($row['mechanic_id']=='4') {$mechanic_id = 12;}
    //        if ($row['mechanic_id']=='26') {$mechanic_id = 13;}
    //        if ($row['mechanic_id']=='24') {$mechanic_id = 14;}
    //        if ($row['mechanic_id']=='21') {$mechanic_id = 15;}
    //        if ($row['mechanic_id']=='12') {$mechanic_id = 17;}
    //        if ($row['mechanic_id']=='15') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='22') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='9') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='11') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='10') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='13') {$mechanic_id = 18;}
    //        if ($row['mechanic_id']=='16') {$mechanic_id = 19;}


           
    //        if ($row['place_id']=='1') {$placeId = 2;}
    //        if ($row['place_id']=='2') {$placeId = 3;}
    //        if ($row['place_id']=='3') {$placeId = 4;}
    //        if ($row['place_id']=='4') {$placeId = 5;}
    //        if ($row['place_id']=='5') {$placeId = 6;}
    //        if ($row['place_id']=='6') {$placeId = 7;}
    //        if ($row['place_id']=='7') {$placeId = 8;}
    //        if ($row['place_id']=='10') {$placeId = 11;}
    //        if ($row['place_id']=='12') {$placeId = 13;}
    //        if ($row['place_id']=='8') {$placeId = 9;}
    //        if ($row['place_id']=='11') {$placeId = 12;}
    //        if ($row['place_id']=='13') {$placeId = 14;}
    //        if ($row['place_id']=='14') {$placeId = 15;}
    //        if ($row['place_id']=='9') {$placeId = 10;}
    //        if ($row['place_id']=='999') {$placeId = 1;}

    //        $failure->setTypeId($tid);
    //        $failure->setUserId($user_id);
    //        $failure->setDateCreate($row['date_edit']);
    //        $failure->setDateService($row['ready_date']);
    //        $failure->setUserService(1);
    //        $failure->setServiceText($row['comment_it']);
    //        $failure->setSerial((int)$row['device_serial']);
    //        $failure->setUid($row['item_id']);
    //        $failure->setMechanicId((int)$mechanic_id);
    //        $failure->setRegionId((int)$placeId);
    //        $failure->save();
    //     }
        
    // }
    
}

<?php
namespace Gtm\Controllers;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Failures\Failure;
use Gtm\Models\HiredMachines\HiredMachine;
use Gtm\Models\MachinesFixed\MachineFixed;
use Gtm\Models\OffensesHC\OffenseHC;
use Gtm\Models\Regions\Region;
use Gtm\Models\Users\User;
use Gtm\Models\Parkings\Parking;
use Gtm\Models\Users\UsersAuthService;
use Gtm\Models\WialonAccounts\WialonAccount;

ini_set('display_errors',1);
error_reporting(E_ALL);
class HiredMachineController extends AbstractController
{

//     public function getMachineInfo(string $uid) 
//     {
//         //получаем данные из функции и рендерим шаблон
//         $machine = Machine::getMachineInfo($uid);
//         $users = User::findAllByColumn('role_id',2);
//         $mechanics1 = User::findAllByColumn('role_id',4); // механики
//         $mechanics2 = User::findAllByColumn('role_id',6); //руководители площадок
//         $mechanics = array_merge($mechanics1, $mechanics2);
//         $fixedMechanicId = $fixedRegionId = 0;
//         $regions = Region::findAll();
//         $parking = Parking::getActiveParking($uid);

//         if (MachineFixed::getFixedInfo($uid) <> null) {
//             $fixedMechanicId = MachineFixed::getFixedInfo($uid)['user_id'];
//             $fixedRegionId = MachineFixed::getFixedInfo($uid)['region_id'];
//         }
         
   
//         //возвращаем шаблон с данными в getMachineProp => html
//         return $this->view->renderHtml('main/blocks/machine.php',[
//                     'machine'=>$machine,
//                     'solvUsers'=>$users,
//                     'mechanics'=>$mechanics,
//                     'regions'=>$regions,
//                     'fixedMechanicId'=>$fixedMechanicId,
//                     'fixedRegionId'=>$fixedRegionId,
//                     'parking'=>$parking,
//                 ]);
       
//     }

//    public function changeMechanic($uid, $id)
//    {
//         if(MachineFixed::findOneByColumn('uid', $uid) <> null){
//             $mFixed = MachineFixed::findOneByColumn('uid', $uid);
//             $mFixed->setUserId($id);
//             $mFixed->save();
//         } else {
//             $mFixed = new MachineFixed();
//             $mFixed->setUid($uid);
//             $mFixed->setUserId($id);
//             $mFixed->save();
//         }
//         $failures = Failure::getActiveFailuresByUid($uid);
//         foreach($failures as $failure)
//         {
//             $failure->setMechanicId($id);
//             $failure->save();
//         }
//    }
    
//    public function changeRegion($uid, $id)
//    {
//     if (MachineFixed::findOneByColumn('uid', $uid) <> null) {
//         $mFixed = MachineFixed::findOneByColumn('uid', $uid);
//         $mFixed->setRegionId($id);
//         $mFixed->save();
//     } else {
//         $mFixed = new MachineFixed();
//         $mFixed->setRegionId($id);
//         $mFixed->setUid($uid);
//         $mFixed->save();
//     }
//     $failures = Failure::getActiveFailuresByUid($uid);
//     foreach($failures as $failure)
//     {
//         $failure->setRegionId($id);
//         $failure->save();
//     }
    
//    }

public function showHiredCar($pageNumber)
{
    $hiredCars = HiredMachine::findAll();
    $wialonAccounts = WialonAccount::findAll();
    $this->view->renderHtml('main/hiredCars.php',[
        'hiredCars'=>$hiredCars,
        'wialonAccounts'=>$wialonAccounts,
    ]);
}

public function showHCarInfo($id)
{
    $hCar = HiredMachine::getById($id);
    $wialonAccount = WialonAccount::getById($hCar->getAccountId());
    $this->view->renderHtml('main/blocks/showHCarInfo.php',[
        'hCar'=>$hCar,
        'wialonAccount'=>$wialonAccount,
        
    ]);
}

public function addOffensesHiredCar($type,$id,$q)
{
// var_dump($type);
// var_dump($id);
// var_dump($q);
$user = UsersAuthService::getUserByToken();
if ($q>0) {
    $offensesHC = new OffenseHC();
    $offensesHC->setCarId($id);
    $offensesHC->setType($type);
    $offensesHC->setDateDetection(date('Y-m-d'));
    $offensesHC->setUserId((int)$user->getId());
    $offensesHC->setOverQ($q);
    // var_dump($offensesHC);
    $offensesHC->save();
    if($offensesHC) {
        echo "<span class='alert alert-success' role='alert'>Нарушение добавлено!</span>";
        } 
    }else {
            echo "<span  class='alert alert-danger' role='alert'>ОШИБКА!</span>";
        }
}

// ["carId":protected]=>
// string(2) "87"
// ["userId":protected]=>
// string(1) "1"
// ["type":protected]=>
// string(8) "overTime"
// ["dateDetection":protected]=>
// string(10) "2022-12-27"
// ["overQ":protected]=>
// int(7)
// ["id":protected]=>
// NULL



}
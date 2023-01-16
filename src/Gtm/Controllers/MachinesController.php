<?php
namespace Gtm\Controllers;

use Gtm\Models\Companys\Company;
use Gtm\Models\Coords\Coord;
use Gtm\Models\Coords\CoordPlant;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Failures\Failure;
use Gtm\Models\Fences\Fence;
use Gtm\Models\MachinesFixed\MachineFixed;
use Gtm\Models\Regions\Region;
use Gtm\Models\Users\User;
use Gtm\Models\Parkings\Parking;
use Gtm\Models\Schemas\GeoSchema;

ini_set('display_errors',1);
error_reporting(E_ALL);
class MachinesController extends AbstractController
{

    public function getMachineInfo(string $uid) 
    {
        //получаем данные из функции и рендерим шаблон
        $machine = Machine::getMachineInfo($uid);
        $users = User::findAllByColumn('role_id',2);
        $mechanics1 = User::findAllByColumn('role_id',4); // механики
        $mechanics2 = User::findAllByColumn('role_id',6); //руководители площадок
        $mechanics = array_merge($mechanics1, $mechanics2);
        $fixedMechanicId = $fixedRegionId = 0;
        $regions = Region::findAll();
        $parking = Parking::getActiveParking($uid);

        if (MachineFixed::getFixedInfo($uid) <> null) {
            $fixedMechanicId = MachineFixed::getFixedInfo($uid)['user_id'];
            $fixedRegionId = MachineFixed::getFixedInfo($uid)['region_id'];
        }
    
        $forageUid = GeoSchema::getParamByName('forageUid');
        $granariesUid = GeoSchema::getParamByName('granariesUid');
        $granariesFences = Fence::findAllByColumn('guid', $granariesUid);

        return $this->view->renderHtml('main/blocks/machine.php',[
                    'machine'=>$machine,
                    'solvUsers'=>$users,
                    'mechanics'=>$mechanics,
                    'regions'=>$regions,
                    'fixedMechanicId'=>$fixedMechanicId,
                    'fixedRegionId'=>$fixedRegionId,
                    'parking'=>$parking,
                    'forageUid' => $forageUid,
                    'granariesUid' => $granariesUid,
                    'granariesFences' => $granariesFences,
                ]);
       
    }

   public function changeMechanic($uid, $id)
   {
        if(MachineFixed::findOneByColumn('uid', $uid) <> null){
            $mFixed = MachineFixed::findOneByColumn('uid', $uid);
            $mFixed->setUserId($id);
            $mFixed->save();
        } else {
            $mFixed = new MachineFixed();
            $mFixed->setUid($uid);
            $mFixed->setUserId($id);
            $mFixed->save();
        }
        $failures = Failure::getActiveFailuresByUid($uid);
        foreach($failures as $failure)
        {
            $failure->setMechanicId($id);
            $failure->save();
        }
   }
    
   public function changeRegion($uid, $id)
   {
    if (MachineFixed::findOneByColumn('uid', $uid) <> null) {
        $mFixed = MachineFixed::findOneByColumn('uid', $uid);
        $mFixed->setRegionId($id);
        $mFixed->save();
    } else {
        $mFixed = new MachineFixed();
        $mFixed->setRegionId($id);
        $mFixed->setUid($uid);
        $mFixed->save();
    }
    $failures = Failure::getActiveFailuresByUid($uid);
    foreach($failures as $failure)
    {
        $failure->setRegionId($id);
        $failure->save();
    }
    
   }

   public function getStops($serial, $dateStart, $dateEnd, $timeBegin, $timeEnd, $mashineUid)
   {
    $company = Company::getById(1);
    $token = $company->getAgToken();
    $url = $company->getAgServer();
    $schemaId = GeoSchema::getParamByName('schemaId');
    $timeBegin = str_replace(':','',$timeBegin);
    $timeEnd = str_replace(':','',$timeEnd);
    $points = $this->getCoordPointByDate($serial, $dateStart, $dateEnd, $timeBegin, $timeEnd, $mashineUid, $token, $url, $schemaId);
    
    $movementCharacter = $this->getMovementEfforts($points,$token, $url, $schemaId);
    // $stopsSpeed0 = $this->getStopsSpeed0($points); //получаем точки, со скоростью 0
    
    // reset($stops);
    
    // if ($dateStart == $dateEnd && current($stops)['type'] == 'road') { //если запрос стандартный т.е. даты равны (означает что происходит первый запрос) и первая остановка в сутках на дороге
    //     $dateStart = date('Y-m-d', strtotime($dateStart . '- 1 day')); // сдвигаем стартовую дату на день раньше 
    //     $this->getStops($serial, $dateStart, $dateEnd, $mashineUid); //запрашиваем новый список остановок (рекурсии не будет т.к. сработает условие по равенству дат)
    // }

    return $this->view->renderHtml('main/blocks/stops.php',[
        'mashineUid'=>$mashineUid,
        'stops'=>$movementCharacter,

    ]); 
   }

   protected function getCoordPointByDate($serial, $dateStart, $dateEnd, $timeBegin, $timeEnd, $mashineUid, $token, $url, $schemaId)
   {
    // переводит в массив все контрольные точки, переданные прибором за указанный период, относящиеся к данному ТС

    $start = date('Ymd-'.$timeBegin,strtotime($dateStart));
    $end = date('Ymd-'.$timeEnd,strtotime($dateEnd));

    $getUrl = 'https://'.$url.'/ServiceJSON/GetTrack?session='.$token.'&schemaID='.$schemaId.'&IDs='.$serial.'&SD='.$start.'&ED='.$end.'&tripSplitterIndex=-1';
    // echo "<a target='_blank' href='".$getUrl."'>Запрос на сервер ".$url."</a>";
    $result = file_get_contents($getUrl);
    $arr = json_decode($result, true);
    $arrUid = [];// массив объектов АвтоГраф, с параметрами перемещения, на которых был установлен прибор с серийным номером $serial
    foreach($arr as $uid => $value)
    {
        $arrUid[$uid]['time'] = $value[0]['DT']; //время в формате string(20) "2022-12-30T10:40:43Z"
        $arrUid[$uid]['speed'] = $value[0]['Speed']; //скорость float(1.73)
        $arrUid[$uid]['x'] = $value[0]['Lat']; //x координата float(54.6026892)
        $arrUid[$uid]['y'] = $value[0]['Lng']; //y координата float(46.1032058)
    }
    foreach($arrUid[$uid]['time'] as &$ts)
    {
        $ts = date('d.m.Y H:i:s', strtotime($ts));
    }
    
    $allPointByUid = [];
    for ($i=0; $i < count($arrUid[$mashineUid]['time']); $i++) { 
        $allPointByUid[$i]['id'] = $i;
        $allPointByUid[$i]['time'] = $arrUid[$mashineUid]['time'][$i];
        $allPointByUid[$i]['speed'] = $arrUid[$mashineUid]['speed'][$i];
        $allPointByUid[$i]['x'] = $arrUid[$mashineUid]['x'][$i];
        $allPointByUid[$i]['y'] = $arrUid[$mashineUid]['y'][$i];
        $allPointByUid[$i]['type'] = '';
    }

    return $allPointByUid;
   }

    protected function getStopsSpeed0($allPointByUid)
    {
        $noSpeed = array_filter($allPointByUid['speed'], function($var){return $var < 1;}); //формируем новый массив со значением скорости по условию
        $timeNoSpeed = array_intersect_key($allPointByUid['time'], $noSpeed); //вынимаем из массива времени элементы с ключами массива нулевой скорости $noSpeed
        $xNoSpeed = array_intersect_key($allPointByUid['x'], $noSpeed); //вынимаем из массива x-координат элементы с ключами массива нулевой скорости $noSpeed
        $yNoSpeed = array_intersect_key($allPointByUid['y'], $noSpeed); //вынимаем из массива y-координат элементы с ключами массива нулевой скорости $noSpeed
        
        $pointNoSpeed=[];
        $pointNoSpeed['speed'] = $noSpeed;
        $pointNoSpeed['time'] = $timeNoSpeed;
        $pointNoSpeed['x'] = $xNoSpeed;
        $pointNoSpeed['y'] = $yNoSpeed;

        // R^2 = (x-a)^2 + (y-b)^2 формула окружности, координаты центра (а; b), координаты любой точки окружности (х; у)


        // $filteredStops = [];
        // $stops = [];
        // $noSpeed = array_filter($arrUid['speed'], function($var){return $var == 0;}); //формируем новый массив со значением скорости по условию
        // $timeNoSpeed = array_intersect_key($arrUid['time'], $noSpeed); //вынимаем из массива времени элементы с ключами массива нулевой скорости $noSpeed
        // $xNoSpeed = array_intersect_key($arrUid['x'], $noSpeed); //вынимаем из массива x-координат элементы с ключами массива нулевой скорости $noSpeed
        // $yNoSpeed = array_intersect_key($arrUid['y'], $noSpeed); //вынимаем из массива y-координат элементы с ключами массива нулевой скорости $noSpeed
        // $filteredStops['time'] = $timeNoSpeed;
        // $filteredStops['speed'] = $noSpeed;
        // $filteredStops['x'] = $xNoSpeed;
        // $filteredStops['y'] = $yNoSpeed;
        // $fences= [];
        // $plantsUid = Coord::getAllUidByType('plantsUid');
        // foreach($plantsUid as $pUid) //формируем массив Площадок с координатами
        // {
        //     $fence = Fence::findOneByColumn('uid',$pUid);
        //     $fence->setXCoords(Coord::getXCoords($pUid));
        //     $fence->setYCoords(Coord::getYCoords($pUid));
        //     $fences[] = $fence;
        // }

        // foreach($filteredStops['x'] as $k => $x)
        // {
        //     if (!$this->stopOnCompaniesPlant($x,$filteredStops['y'][$k])) { //если остановк была не на площадке компании (площадка погрузки исключается)
        //         $stops[$k]['x'] = $x;
        //         $stops[$k]['y'] = $filteredStops['y'][$k];
        //         $stops[$k]['type'] = 'road';
        //         $stops[$k]['time0'] = $filteredStops['time'][$k];
        //         $stops[$k]['speed'] = $filteredStops['speed'][$k];
        //         $stops[$k]['timeBegin'] ='';
        //         $stops[$k]['timeEnd'] ='';
        //         if ($this->stopOnForageLoad($x,$filteredStops['y'][$k])) { // если остановка была на погрузке корма
        //             // echo $k."=> ".$filteredStops['time'][$k].": ".$x.", ".$filteredStops['y'][$k]." ПОГРУЗКА!!! <br>";
        //             $stops[$k]['x'] = $x;
        //             $stops[$k]['y'] = $filteredStops['y'][$k];
        //             $stops[$k]['type'] = 'load';
        //             $stops[$k]['time0'] = $filteredStops['time'][$k];
        //             $stops[$k]['speed'] = $filteredStops['speed'][$k];
        //             $stops[$k]['timeBegin'] ='';
        //             $stops[$k]['timeEnd'] ='';
        //             continue;
        //         }
        //         // echo  $k."=> ".$filteredStops['time'][$k].": ".$x.", ".$filteredStops['y'][$k]." <a target='_blank' href='https://yandex.ru/maps/?pt=".$filteredStops['y'][$k].",".$x."&z=18&l=map'>карта</a><br>";
        //     } else {
        //         $stops[$k]['x'] = $x;
        //         $stops[$k]['y'] = $filteredStops['y'][$k];
        //         $stops[$k]['type'] = 'plant';
        //         $stops[$k]['time0'] = $filteredStops['time'][$k];
        //         $stops[$k]['speed'] = $filteredStops['speed'][$k];
        //         $stops[$k]['timeBegin'] ='';
        //         $stops[$k]['timeEnd'] ='';
        //     }
        // }
        
        // фильтруем остановки. Скрываем частые остановки на площадках. Оставляем только крайние для того чтобы контролировать дальнейшие остановки, порожние и с грузом, на дорогах.
        // $filterPlantStop = [];
        // while ($stop = current($stops)) 
        // {
        //     end($filterPlantStop);
        //     if (current($filterPlantStop)['type'] == current($stops)['type'] && (current($stops)['type'] == 'plant')) {
        //         next($stops);    
        //     } else {
        //         $filterPlantStop[key($stops)] = current($stops);
        //         next($stops);
        //     }          
        // }

        return $pointNoSpeed;

        // var_dump($filterPlantStop);
    }

    protected function getMovementEfforts($points, $token, $url, $schemaId)
    {
        //можно получить с сервера АвтоГраф либо из предварительно сформированных в БД gtm        
        // $getUrl = "https://".$url."/ServiceJSON/GetGeofences?session=".$token."&schemaID=".$schemaId."&IDs=".$farmFencesGroupUid;
        // $result = file_get_contents($getUrl);
        // $arr = json_decode($result, true);
        $uidsAllFarmsFences = Coord::getAllUidByType('farmsUid');
        $allFarnFences = [];
        foreach($uidsAllFarmsFences as $uid)
        {
            $fence = Fence::findOneByColumn('uid',$uid);
            $fence -> setXCoords(Coord::getXCoords($uid));
            $fence -> setYCoords(Coord::getYCoords($uid));
            $allFarnFences[] = $fence;
        }

        

        // $farmFences = 
        $movementCharacter = [];
        for ($i=1; $i < count($points); $i++) { 
            if (($points[$i-1]['x'] <> $points[$i]['x'] || $points[$i-1]['y'] <> $points[$i]['y'])) {
                $points[$i-1]['type'] = 'move';
            } else {
                $points[$i-1]['type'] = 'stop';
            }
           
        }

        return $allFarnFences;
    }

    protected function stopOnForageLoad($x,$y)
    {
        // $fenceFL = Fence::findOneByColumn('uid', GeoSchema::getParamByName('forageUid'));
        // return $this->inPoly($x, $y, $fenceFL);
    }

    protected function stopOnCompaniesPlant($x,$y)
    {
        // $onPlant = false;
        // $fenceFL[] = Fence::findOneByColumn('uid', GeoSchema::getParamByName('forageUid'));
        // $companiesPlant = Coord::getCompaniesPlants();
        
        // foreach($companiesPlant as $plant)
        // {
        //     if ($this->inPoly($x, $y, $plant)) {
        //        return true;
        //     }
        // }
        // return $onPlant;
    }

    // protected function getFarmsFenceGuid()
    // {
    //     $farmFencesGuid['value'] = GeoSchema::getParamByName('farmsUid');
    //     return $farmFencesGuid;
    // }

       protected function inPoly($x, $y, $fence){ //проверка на принадлежность точки с координатами определенному объекту полигон
        $xp = $fence->getXCoords();
        $yp = $fence->getYCoords();

         $npol = count($xp);
         $j = $npol - 1;
         $c = false;
         for ($i = 0; $i < $npol; $i++){
             if (((($yp[$i]<=$y) && ($y<$yp[$j])) || (($yp[$j]<=$y) && ($y<$yp[$i]))) &&
             ($x > ($xp[$j] - $xp[$i]) * ($y - $yp[$i]) / ($yp[$j] - $yp[$i]) + $xp[$i])) {
              $c = !$c;
              }
              $j = $i;
         }
       return $c;
   }


   
}
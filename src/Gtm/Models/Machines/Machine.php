<?php
namespace Gtm\Models\Machines;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Models\Properties\Properties;
use Gtm\Services\Db;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Users\User;
use Gtm\Models\Failures\Failure;
use Gtm\Models\Offenses\Offense;

class Machine extends ActiveRecordEntity
{
    /** @var string*/
    protected $uid;

    /** @var string*/
    protected $guid;

        /** @var string*/
        protected $name;

    public static function getTableName()
    {
        return 'machines';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setUid($newUid)
    {
        $this->uid = $newUid;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setGuid($newGuid)
    {
        $this->guid = $newGuid;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getSerial()
    {
        
        $uid = $this->uid;

        $db = Db::getInstance();  
        $result = $db->queryAssoc('SELECT value FROM `properties` WHERE uid = :uid && name = :name;', [':uid' => $uid, ':name' => 'glonass_serial',]);
        
        if (count($result)>0) {
            return $result[0]['value'];    
        }
        return null;  
    }

    public static function saveToBase($machinesArray)
    {
        foreach($machinesArray as $machineArray)
        {
            $machine = new Machine();
            $machine->setUid(!empty($machineArray['uid'])? $machineArray['uid']:'');
            $machine->setGuid(!empty($machineArray['guid'])? $machineArray['guid']:'');
            $machine->setName(!empty($machineArray['name'])? $machineArray['name']:'');
            $machine->save();
        }
    }

    public static function getInnerMachines($guid)
    {
        $db = Db::getInstance();  
        $thisMachinesArr = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE guid = :guid ;', [':guid' => $guid]);
        $arrMachine = null;
        foreach($thisMachinesArr as $machine)
        {
            $arrMachine[$machine['uid']]['uid'] = $machine['uid'];
            $arrMachine[$machine['uid']]['guid'] = $machine['guid'];
            $arrMachine[$machine['uid']]['name'] = $machine['name'];
        }
        if (count($arrMachine)>0) {
            return $arrMachine;    
        }
        return [];        
    }

    public static function getMachineInfo($uid) //формируем данные для передачи в рендер
    {
        $machine = []; //массив со всеми свойствами машины
        $db = Db::getInstance();  
        $thisMachine = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid ;', [':uid' => $uid]);
        
        $propertiesType = $db->queryAssoc('SELECT * FROM `propertiesType` 
        LEFT JOIN `properties` ON propertiesType.name=properties.name 
        LEFT JOIN `machinesFixed` ON machinesFixed.uid=:uid 
        WHERE properties.uid = :uid && propertiesType.is_uses = :is_uses ORDER BY sort DESC;', 
        [':is_uses' => 1, ':uid' => $uid]);
        
        $machine['name'] = $thisMachine[0]['name'];
        $machine['uid'] = $thisMachine[0]['uid'];
        $machine['guid'] = $thisMachine[0]['guid'];
        
        $doneFailures = Failure::getDoneFailuresByUid($uid);
        $activeFailures = Failure::getArrayActiveFailuresByUid($uid);
        $offenses = Offense::getOffensesByUid($uid);

        foreach($propertiesType as $type)
        {
            $machine['properties'][$type['name']]['name'] = $type['description'];
            $machine['properties'][$type['name']]['value'] = Properties::getActiveByUid($uid, $type['name']);
            $machine['properties'][$type['name']]['sort'] = $type['sort'];
            $machine['properties'][$type['name']]['is_basic'] = $type['is_basic'];
            $machine['fixed']['region_id'] = $type['region_id'];
            $machine['fixed']['user_id'] = $type['user_id'];
        }
        $machine['doneFailures'] = $doneFailures;
        $machine['activeFailures'] = $activeFailures;
        $machine['offenses'] = $offenses;

        return $machine; 
    }

    public static function getCountActionMachine()
    {
        $db = Db::getInstance();
        $allActionGroups = $db->queryAssoc('SELECT * FROM `groups` WHERE is_blocked IS not true;',[]);
        $allMachines = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE 1;',[]);
        foreach($allActionGroups as $group){
            $actionGroupsUid[] = $group['self_guid'];
        }
        
        $activeMachineUid=[];
        foreach($allMachines as $machine){
            if (in_array($machine['guid'],$actionGroupsUid)) {
                $activeMachineUid[] = $machine['uid'];
            }
        }
        return $activeMachineUid;
    }

}
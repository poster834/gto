<?php
namespace Gtm\Models\Machines;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;

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
        $thisMachimesArr = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE guid = :guid ;', [':guid' => $guid]);
        $arrMachine = null;
        foreach($thisMachimesArr as $machine)
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
}
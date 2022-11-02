<?php
namespace Gtm\Models\Machines;

use Gtm\Models\ActiveRecordEntity;

class Machine extends ActiveRecordEntity
{
    /** @var string*/
    protected $uid;

    /** @var string*/
    protected $guid;

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

    public static function saveToBase($machinesArray)
    {
        foreach($machinesArray as $machineArray)
        {
            $machine = new Machine();
            $machine->setUid(!empty($machineArray['uid'])? $machineArray['uid']:'');
            $machine->setGuid(!empty($machineArray['guid'])? $machineArray['guid']:'');
            $machine->save();
        }
    }
}
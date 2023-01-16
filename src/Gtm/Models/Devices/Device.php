<?php
namespace Gtm\Models\Devices;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Models\Machines\Machine;
use Gtm\Services\Db;


class Device extends ActiveRecordEntity
{
        /** @var string*/
        protected $glonassSerial;
        
        /** @var string*/
        protected $uid;

    protected static function getTableName()
    {
        return 'devices';
    }
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getGlonassSerial()
    {
        return $this->glonassSerial;
    }

    public function getUid()
    {
        return $this->uid;
    }


    public function setGlonassSerial($newGlonassSerial)
    {
        $this->glonassSerial = $newGlonassSerial;
    }

    public function setUid($newUid)
    {
        $this->uid = $newUid;
    }

    public function updateFromArray(array $fields)
    {
        return $this;
    }

    public static function saveToBase($devices)
    {
        foreach($devices as $uid => $serial)
        {
            $device = new Device();
            $device->setGlonassSerial($serial);
            $device->setUid($uid);
            $device->save();
        }
    }

    public static function getCountActionDevice()
    {
        $actionMachinesUid = Machine::getCountActionMachine();

        $db = Db::getInstance();
        $allDevices = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE 1;',[]);
        $allActiveDevicesSN = [];
        foreach($allDevices as $device){
            if (in_array($device['uid'],$actionMachinesUid)) {
                $allActiveDevicesSN[]=$device['glonass_serial'];
            }   
        }
        return ($allActiveDevicesSN);
    }
}
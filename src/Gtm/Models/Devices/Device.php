<?php
namespace Gtm\Models\Devices;

use Gtm\Models\ActiveRecordEntity;


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
}
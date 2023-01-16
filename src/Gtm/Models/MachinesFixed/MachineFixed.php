<?php
namespace Gtm\Models\MachinesFixed;

use Gtm\Controllers\MachinesController;
use Gtm\Models\ActiveRecordEntity;
use Gtm\Models\Properties\Properties;
use Gtm\Services\Db;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Users\User;
use Gtm\Models\Failures\Failure;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Offenses\Offense;

class MachineFixed extends ActiveRecordEntity
{
    /** @var string*/
    protected $uid;

    /** @var string*/
    protected $regionId;

        /** @var string*/
        protected $userId;

    public static function getTableName()
    {
        return 'machinesFixed';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setUid($newUid)
    {
        $this->uid = $newUid;
    }

    public function setRegionId($regionId)
    {
        $this->regionId = $regionId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRegionId()
    {
        return $this->regionId;
    }


public static function getFixedInfo($uid)
{
    $db = Db::getInstance();
    $result = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid ;', [':uid' => $uid]);
    if ($result === []) {
        return null;
    }
    return $result[0];
}


 
}
<?php
namespace Gtm\Models\Parkings;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\Users\User;


// ini_set('display_errors',1);
// error_reporting(E_ALL);
	

// uid
// user_id
// date_stop
// date_start
// mechanic_id
// garage_id

class Parking extends ActiveRecordEntity
{
            /** @var string*/
            protected $uid;
        
            /** @var string*/
            protected $userId;
            
            /** @var string*/
            protected $dateStop;
            
            /** @var string*/
            protected $dateStart;

            /** @var string*/
            protected $parkingReason; //ENUM 'temporary_driver','conservation','repair','reserve'
            
            /** @var string*/
            protected $comment;
            
            /** @var string*/
            protected $garageId;

            public function setUid($Uid)
            {
                $this->uid = $Uid;
            }
            public function getUid()
            {
                return $this->uid;
            }

            public function setComment($val)
            {
                $this->comment = $val;
            }
            public function getComment()
            {
                if ($this->comment <> NULL) {
                    return $this->comment;
                } else {
                    return '';
                }
                
            }

            public function setParkingReason($ParkingReason)
            {
                $this->parkingReason = $ParkingReason;
            }
            public function getParkingReason()
            {
                return $this->parkingReason;
            }

            public function setUserId($UserId)
            {
                $this->userId = $UserId;
            }
            public function getUserId()
            {
                return $this->userId;
            }

            public function setDateStop($DateStop)
            {
                $this->dateStop = $DateStop;
            }
            public function getDateStop()
            {
                return $this->dateStop;
            }

            public function setDateStart($DateStart)
            {
                $this->dateStart = $DateStart;
            }
            public function getDateStart()
            {
                return $this->dateStart;
            }

            public function setGarageId($GarageId)
            {
                $this->garageId = $GarageId;
            }
            public function getGarageId()
            {
                return $this->garageId;
            }

    protected static function getTableName()
    {
        return 'parkings';
    }
    
    protected static function getCountPerPage()
    {
        return 1;
    }

    public static function getActiveParking($uid)
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid && date_start IS NULL;', [':uid' => $uid], static::class);
        if(count($result) > 0)
        {
            return $result[0];
        }
        return null;
    }
}
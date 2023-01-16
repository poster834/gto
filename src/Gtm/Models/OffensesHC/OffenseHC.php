<?php
namespace Gtm\Models\OffensesHC;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class OffenseHC extends ActiveRecordEntity
{

/** @var int*/
protected $carId;

/** @var int*/
protected $userId;

/** @var string*/
protected $type;

/** @var string*/
protected $dateDetection;

/** @var int*/
protected $overQ;
   
    
    protected static function getTableName()
    {
        return 'offenses_hired_car';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }


    public function getCarId()
    {
        return $this->carId;
    }
    public function setCarId($val)
    {
        $this->carId = $val;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId($val)
    {
        $this->userId = $val;
    }

    public function getType()
    {
        return $this->type;
    }
    public function setType($val)
    {
        $this->type = $val;
    }

    public function getDateDetection()
    {
        return $this->dateDetection;
    }
    public function setDateDetection($val)
    {
        $this->dateDetection = $val;
    }
   
    public function getOverQ()
    {
        return $this->overQ;
    }
    public function setOverQ($val)
    {
        $this->overQ =(int)$val;
    }

    


}
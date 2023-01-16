<?php
namespace Gtm\Models\Offenses;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class Offense extends ActiveRecordEntity
{

/** @var int*/
protected $typeId;

/** @var int*/
protected $userId;

/** @var string*/
protected $dateCreate;

/** @var string*/
protected $photo;

/** @var string*/
protected $dateAlert;

/** @var string*/
protected $description;

/** @var string*/
protected $notifiedUser;

/** @var string*/
protected $uid;

/** @var int*/
protected $serial;
   
/** @var string*/
protected $comments;

/** @var float*/
protected $beginCoordX;

/** @var float*/
protected $beginCoordY;

/** @var float*/
protected $endCoordX;

/** @var float*/
protected $endCoordY;

/** @var string*/
protected $beginTime;

/** @var string*/
protected $endTime;
    
    protected static function getTableName()
    {
        return 'offenses';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getUid()
    {
        return $this->uid;
    }
    
    public function setUid($val)
    {
        $this->uid = $val;
    }

    public function getComments()
    {
        return $this->comments;
    }
    
    public function setComments($val)
    {
        $this->comments = $val;
    }


public function getSerial()
{
    return $this->serial;
}

public function setSerial($val)
{
    $this->serial = $val;
}

public function getNotifiedUser()
{
    return $this->notifiedUser;
}

public function setNotifiedUser($val)
{
    $this->notifiedUser = $val;
}

public function getDescription()
{
    return $this->description;
}

public function setDescription($val)
{
    $this->description = $val;
}

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function setTypeId($val)
    {
        $this->typeId = $val;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($val)
    {
        $this->userId = $val;
    }
    
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    public function setDateCreate($val)
    {
        $this->dateCreate = $val;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($val)
    {
        $this->photo = $val;
    }

    public function getDateAlert()
    {
        return $this->dateAlert;
    }

    public function setDateAlert($val)
    {
        $this->dateAlert = $val;
    }

    public function getBeginCoordX()
    {
        return $this->beginCoordX;
    }
    public function setBeginCoordX($val)
    {
        $this->beginCoordX = $val;
    }

    public function getBeginCoordY()
    {
        return $this->beginCoordY;
    }
    public function setBeginCoordY($val)
    {
        $this->beginCoordY = $val;
    }

    public function getEndCoordX()
    {
        return $this->endCoordX;
    }
    public function setEndCoordX($val)
    {
        $this->endCoordX = $val;
    }

    public function getEndCoordY()
    {
        return $this->endCoordY;
    }
    public function setEndCoordY($val)
    {
        $this->endCoordY = $val;
    }

    public function getBeginTime()
    {
        return $this->beginTime;
    }
    public function setBeginTime($val)
    {
        $this->beginTime = $val;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }
    public function setEndTime($val)
    {
        $this->endTime = $val;
    }
    


    public static function getOffensesByUid($uid)
    {
        $db = Db::getInstance();
        $offenses = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid ;', [':uid' => $uid], static::class);
        foreach($offenses as $offense){
            $offenseComments = $db->query('SELECT * FROM `offensesComments` WHERE offense_id = :offense_id ;', [':offense_id' => $offense->getId()]);
            $offense->setComments($offenseComments);
        }
        
        return $offenses;
    }

    public function updateFromArray(array $fields)
    {
        // $testUni = FailuresType::uniquenessColumnTest($fields,'name');

        // if (empty($fields['name'])) {
        //     throw new InvalidArgumentException("Не указано поле: Имя");
        // }
   
        // if (!$testUni) {
        //     throw new InvalidArgumentException("Значение поля 'Имя:' не уникально");
        // }
        
        // $this->setName($fields['name']);
        // $this->save();
        // return $this;
    }
}
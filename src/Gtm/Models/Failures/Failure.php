<?php
namespace Gtm\Models\Failures;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\Users\User;


// ini_set('display_errors',1);
// error_reporting(E_ALL);

class Failure extends ActiveRecordEntity
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
protected $dateService;

/** @var string*/
protected $description;

/** @var int*/
protected $userService;

/** @var string*/
protected $uid;

/** @var int*/
protected $serial;
   
/** @var string*/
protected $serviceText;

/** @var int*/
protected $regionId;

/** @var int*/
protected $mechanicId;

/** @var string*/
protected $alertDate;
    
    protected static function getTableName()
    {
        return 'failures';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getUid()
    {
        return $this->uid;
    }
    
    public function setUid($Uid)
    {
        $this->uid = $Uid;
    }

    public function getServiceText()
    {
        return $this->serviceText;
    }
    
    public function setServiceText($ServiceText)
    {
        $this->serviceText = $ServiceText;
    }


public function getSerial()
{
    return $this->serial;
}

public function setSerial($Serial)
{
    $this->serial = $Serial;
}


public function getUserService()
{
    return $this->userService;
}

public function setUserService($UserService)
{
    $this->userService = $UserService;
}

public function getDescription()
{
    return $this->description;
}

public function setDescription($Description)
{
    $this->description = $Description;
}


    public function getDateService()
    {
        return $this->dateService;
    }

    public function setDateService($DateService)
    {
        $this->dateService = $DateService;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }


    public function setTypeId($TypeId)
    {
        $this->typeId = $TypeId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($UserId)
    {
        $this->userId = $UserId;
    }
    
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    public function setDateCreate($DateCreate)
    {
        $this->dateCreate = $DateCreate;
    }

    public function getAlertDate()
    {
        return $this->alertDate;
    }

    public function setAlertDate($AlertDate)
    {
        $this->alertDate = $AlertDate;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($Photo)
    {
        $this->photo = $Photo;
    }

    public function getMechanicId()
    {
        return $this->mechanicId;
    }

    public function setMechanicId($id)
    {
        $this->mechanicId = $id;
    }

    public function getRegionId()
    {
        return $this->regionId;
    }

    public function setRegionId($id)
    {
        $this->regionId = $id;
    }


    public static function getAllFailuresByUid($uid)
    {
        $db = Db::getInstance();
        $failures = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid ;', [':uid' => $uid]);
        foreach($failures as &$failure)
        {            
            $failure['typeName'] = FailuresType::getById($failure['type_id'])->getName();
            $failure['user_service_name'] = User::getById($failure['user_service'])->getName();
            $failure['user_add_name'] = User::getById($failure['user_id'])->getName();

        }
        return $failures;
    }

    public static function getArrayActiveFailuresByUid($uid)
    {
        //** return [] */
        $db = Db::getInstance();
        $failures = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid && date_service IS NULL;', [':uid' => $uid]);
        foreach($failures as &$failure)
        {            
            $failure['typeName'] = FailuresType::getById($failure['type_id'])->getName();
            $failure['user_add_name'] = User::getById($failure['user_id'])->getName();

        }
        return $failures;
    }

    public static function getActiveFailuresByUid($uid)
    {
        //**@return objects */
        $db = Db::getInstance();
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid && date_service IS NULL;', [':uid' => $uid], static::class);
        return $failures;
    }
    

    public static function getDoneFailuresByUid($uid)
    {
        $db = Db::getInstance();
        $failures = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid && date_service IS NOT NULL;', [':uid' => $uid]);
        foreach($failures as &$failure)
        {            
            $failure['typeName'] = FailuresType::getById($failure['type_id'])->getName();
            $failure['user_service_name'] = User::getById($failure['user_service'])->getName();
            $failure['user_add_name'] = User::getById($failure['user_id'])->getName();

        }
        return $failures;
    }

    public static function getAllMachineFailuresByUid($uid)
    {
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid;', [':uid' => $uid], static::class);
            return $failures;    
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

    public static function findActiveByRegionId($id, $page)
    {
        $countPerPage = static::getCountPerPage();
        $start = 0;

        if ($page > 1) {
            $start = ($page-1) * $countPerPage;
        }
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE region_id = :id && user_service IS NULL LIMIT '.$start.','.$countPerPage.';', [':id' => $id], static::class);
        return $failures; 
    }

    public static function findActiveByTypeId($id, $page)
    {
        $countPerPage = static::getCountPerPage();
        $start = 0;

        if ($page > 1) {
            $start = ($page-1) * $countPerPage;
        }
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE type_id = :id && user_service IS NULL LIMIT '.$start.','.$countPerPage.';', [':id' => $id], static::class);
        return $failures; 
    }


    public static function findServisedByRegionId($id)
    {
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE region_id = :id && user_service IS NOT NULL;', [':id' => $id], static::class);
            return $failures; 
    }

    public static function getActiveFailuresInDirections()
    {
        $db = Db::getInstance(); 
        $result = $db->queryAssoc('SELECT directions.id as direction_id, COUNT(*) as count FROM `'.static::getTableName().'` 
        LEFT JOIN regions ON failures.region_id = regions.id 
        LEFT JOIN directions ON  directions.id=regions.direction
        WHERE `date_service` IS NULL GROUP BY directions.id;',[]);
        return $result; 
    }

    public static function getActiveFailuresInDirectionId($id)
    {
        $db = Db::getInstance(); 
        $result = $db->queryAssoc('SELECT *, directions.id as direction_id, failures.id as failure_id FROM `'.static::getTableName().'` 
        LEFT JOIN regions ON failures.region_id = regions.id 
        LEFT JOIN directions ON  directions.id=regions.direction
        WHERE `date_service` IS NULL && directions.id = :id;',['id'=>$id]);
        return $result; 
    }

    public static function getActivePaginatorPagesByColumn($column, $value)
    {
        $countPerPage = static::getCountPerPage();
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'` WHERE `date_service` IS NULL && `'.$column.'` = :value;',['value'=>$value]);
        $row = (int)($result[0]->count);
        $quantityPage = (int)ceil($row / $countPerPage);
        return $quantityPage;
    }
    
    // public static function getActivePaginatorPagesByTypeId($id)
    // {
    //     $countPerPage = static::getCountPerPage();
    //     $db = Db::getInstance();
    //     $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'` WHERE `date_service` IS NULL && type_id = :id;',['id'=>$id]);
    //     $row = (int)($result[0]->count);
    //     $quantityPage = (int)ceil($row / $countPerPage);
    //     return $quantityPage;
    // }

    public static function activeFailuresAllTypes()
    {
        $db = Db::getInstance(); 
        $result = $db->queryAssoc('SELECT COUNT(*) as count, failuresType.name as failuresTypeName, failuresType.id as failuresTypeId  FROM `'.static::getTableName().'` 
        LEFT JOIN failuresType ON failuresType.id = failures.type_id 
        WHERE `date_service` IS NULL GROUP BY type_id;',[]);
        return $result; 
    }
    

    public static function getActivePeriodFailureByType($firstDay, $lastDay)
    {
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE date_create BETWEEN "'. $firstDay.'" AND "'.$lastDay.'" && user_service IS NULL;', [ ], static::class);
        return $failures; 
    }

    public static function getDonePeriodFailureByType($firstDay, $lastDay)
    {
        $db = Db::getInstance();  
        $failures = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE date_create BETWEEN "'. $firstDay.'" AND "'.$lastDay.'" && user_service IS NOT NULL;', [ ], static::class);
        return $failures; 
    }

    public static function getCountPaginatorPagesDoneFailuresByUid($uid)
    {
        $countPerPage = static::getCountPerPage();
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'` WHERE uid = :uid && `date_service` IS NOT NULL;',['uid'=>$uid]);
        $row = (int)($result[0]->count);
        $quantityPage = (int)ceil($row / $countPerPage);
        return $quantityPage;
    }


    public static function findDoneFailuresPaginatorByUid($uid, $page)
    {
        $countPerPage = static::getCountPerPage();
        $start = 0;

        if ($page > 1) {
            $start = ($page-1) * $countPerPage;
        }
        $db = Db::getInstance();  
        $doneFailures = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid && user_service IS NOT NULL ORDER BY id DESC LIMIT '.$start.','.$countPerPage.' ;', [':uid' => $uid], static::class);
        foreach($doneFailures as &$failure)
        {            
            $failure['typeName'] = FailuresType::getById($failure['type_id'])->getName();
            $failure['user_service_name'] = User::getById($failure['user_service'])->getName();
            $failure['user_add_name'] = User::getById($failure['user_id'])->getName();

        }
        return $doneFailures; 
    }
    
}
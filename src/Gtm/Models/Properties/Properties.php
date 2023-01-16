<?php
namespace Gtm\Models\Properties;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class Properties extends ActiveRecordEntity
{
    /** @var string*/
    protected $name;

    /** @var string*/
    protected $uid;
    
    /** @var string*/
    protected $value;

    /** @var int*/
    protected $isBasic;
    
    protected static function getTableName()
    {
        return 'properties';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIsBasic()
    {
        return $this->isBasic;
    }

    public function getUid()
    {
        return $this->uid;
    }
    
    public function getValue()
    {
        return $this->value;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setUid($newUid)
    {
        $this->uid = $newUid;
    }

    public function setValue($newValue)
    {
        $this->value = $newValue;
    }

    public function setIsBasic($newIsBasic)
    {
        $this->isBasic = $newIsBasic;
    }

    public static function findAllWithCount()
    {
        $propArray=[];
        $db = Db::getInstance();
        $result = $db->query('SELECT name, COUNT(*) as count FROM `'.static::getTableName().'` GROUP BY `name` ;', []);
        foreach($result as $propCount)
        {
            $propArray [$propCount->name] = (int)$propCount->count;
        }
        arsort($propArray);
        return $propArray;
    }

    public static function saveToBase($propertiesArray, $basePropertiesArray)
    {
        foreach($propertiesArray as $propertyUid => $properties)
        {
            foreach($properties as $propK=>$propV)
            {
                $property = new Properties();
                if (in_array($propK, $basePropertiesArray)) {
                    $property->setIsBasic(1);
                } else {
                    $property->setIsBasic(0);
                }
                $property->setUid($propertyUid);
                $property->setName($propK);
                $property->setValue($propV);
                $property->save();
            }
        }
    }

    public static function getActiveByUid($uid, $name)
    {
        $db = Db::getInstance();
        $result = $db->queryAssoc('SELECT value FROM `'.static::getTableName().'` WHERE uid = :uid && name = :name;', [':uid'=>$uid, ':name'=>$name], static::class);
        if ($result == NULL) {
            $result[0]['value'] = 'не указано в АвтоГраф';
        }
        return $result[0]['value'];
    }

    public static function getAllByUid($uid)
    {
        $db = Db::getInstance();
        $result = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE uid = :uid;', [':uid'=>$uid], static::class);
        return $result;
    }

}
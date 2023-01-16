<?php
namespace Gtm\Models\Coords;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Models\Fences\Fence;
use Gtm\Models\Schemas\GeoSchema;
use Gtm\Services\Db;


class Coord extends ActiveRecordEntity
{
    /** @var string*/
    protected $fenceUid;

    /** @var string*/
    protected $parentUid;
    
    /** @var string*/
    protected $type;

    /** @var float*/
    protected $xCoord;

    /** @var float*/
    protected $yCoord;
    
    public function getFenceUid()
    {
        return $this->fenceUid;
    }
    public function setFenceUid($val)
    {
        $this->fenceUid = $val;
    }

    public function getParentUid()
    {
        return $this->parentUid;
    }
    public function setParentUid($val)
    {
        $this->parentUid = $val;
    }

    public function getXCoord()
    {
        return $this->xCoord;
    }
    public function setXCoord($val)
    {
        $this->xCoord = $val;
    }

    public function getType()
    {
        return $this->type;
    }
    public function setType($val)
    {
        $this->type = $val;
    }

    public function getYCoord()
    {
        return $this->yCoord;
    }
    public function setYCoord($val)
    {
        $this->yCoord = $val;
    }  

    public static function getTableName()
    {
        return 'coords';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public static function getXCoords($fenceUid)
    {
        $db = Db::getInstance();  
        $result = $db->queryAssoc('SELECT x_coord FROM `'.static::getTableName().'` WHERE fence_uid = :fence_uid;', [':fence_uid' => $fenceUid]);
        
        if (count($result)>0) {
            foreach($result as $plantUid)
            {
                $plantsUid[] = $plantUid['x_coord'];
            }
            return $plantsUid;    
        }
        return null;           
    }

    public static function getYCoords($fenceUid)
    {
        $db = Db::getInstance();  
        $result = $db->queryAssoc('SELECT y_coord FROM `'.static::getTableName().'` WHERE fence_uid = :fence_uid;', [':fence_uid' => $fenceUid]);
        
        if (count($result)>0) {
            foreach($result as $plantUid)
            {
                $plantsUid[] = $plantUid['y_coord'];
            }
            return $plantsUid;    
        }
        return null;         
    }

    public static function getAllUidByType($type)
    {
        $db = Db::getInstance();  
        $result = $db->queryAssoc('SELECT DISTINCT fence_uid FROM `'.static::getTableName().'` WHERE type = :type;', [':type' => $type]);
        
        if (count($result)>0) {
            foreach($result as $plantUid)
            {
                $plantsUid[] = $plantUid['fence_uid'];
            }
            return $plantsUid;    
        }
        return null;          
    }

    // public static function getCompaniesPlants() //получение всех площадок за исключением площадки погрузки комбикорма
    // {
    //     $loadForageUid = GeoSchema::getParamByName('forageUid'); //получаем uid геозоны погрузки комбикорма
    //     $db = Db::getInstance();  
    //     $result = $db->queryAssoc('SELECT DISTINCT fence_uid FROM `'.static::getTableName().'` WHERE type = :type && fence_uid <> :loadForageUid;', [':type' => 'plant', ':loadForageUid' => $loadForageUid]);
    //     if (count($result)>0) {
    //         foreach($result as $plantUid)
    //         {
    //             $companiesPlants[] = Fence::findOneByColumn('uid', $plantUid['fence_uid']);
    //         }
    //         return $companiesPlants;    
    //     }
    //     return null; 
    // }
}
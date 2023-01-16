<?php
namespace Gtm\Models\Groups;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;
use Gtm\Models\Machines\Machine;

class GeoGroup extends ActiveRecordEntity
{
        /** @var string*/
        protected $name;

            /** @var string*/
        protected $uid;
        
        /** @var string*/
        protected $guid;



        

    public static function getTableName()
    {
        return 'geoGroups';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    


    public function setName($val)
    {
        $this->name = $val;
    }

    public function setUid($val)
    {
        $this->uid = $val;
    }

    public function setGuid($val)
    {
        $this->guid = $val;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getGuid()
    {
        return $this->guid;
    }


    public static function getRootGeoGroup()
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE guid IS NULL;', [], static::class);
        if(count($result) > 0)
        {
            return $result[0];
        } else {
            return null;
        }
    }

    public static function saveToBase($groupsArray)
    {
        foreach($groupsArray as $gr)
        {
            $group = new GeoGroup();
            $group->setName($gr['Name']);
            $group->setUid($gr['ID']);
            $group->setGuid($gr['ParentID']);
            $group->save();
        }
    }

}

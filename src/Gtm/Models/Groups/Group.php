<?php
namespace Gtm\Models\Groups;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;
use Gtm\Models\Machines\Machine;

class Group extends ActiveRecordEntity
{
        /** @var string*/
        protected $name;

            /** @var string*/
        protected $selfGuid;

        /** @var string*/
        protected $parentGuid;

        /** @var int*/
        protected $isBlocked;
        

    public static function getTableName()
    {
        return 'groups';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setSelfGuid($newSelfGuid)
    {
        $this->selfGuid = $newSelfGuid;
    }

    public function setParentGuid($newParentGuid)
    {
        $this->parentGuid = $newParentGuid;
    }
    
    public function setBlocked($block)
    {
        $this->isBlocked = $block;
    }

    public function getName()
    {
        return $this->isBlocked;
    }

    public function getIsBlocked()
    {
        return $this->name;
    }

    public function getSelfGuid()
    {
        return $this->selfGuid;
    }
    public function getParentGuid()
    {
        return $this->parentGuid;
    }

    public static function getBySelfId($selfGuid)
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE self_guid = :selfGuid ;', [':selfGuid' => $selfGuid], static::class);
        return $result[0];
    }

    public static function checkBlockedGroup()
    {
        $db = Db::getInstance();
        $result = $db->queryAssoc('SELECT * FROM `groups` WHERE is_blocked = 1;', []);
        $arr = [];
        foreach($result as $gr){
            $arr[] = $gr['self_guid'];
        }
        return $arr;
    }

    public static function saveToBase($groupsArray,$blockedArr)
    {
        foreach($groupsArray as $groupArr)
        {
            $group = new Group();
            $group->setName($groupArr['Name']);
            $group->setSelfGuid($groupArr['ID']);
            $group->setParentGuid($groupArr['ParentID']);
            $group->setBlocked(in_array($groupArr['ID'], $blockedArr));
            $group->save();
        }
        // self::checkBlockedGroup();
    }



    public static function getInnerGroup($guid)
    {
        $array = null;
        $db = Db::getInstance();
        $innerGroups = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE parent_guid = :parent_guid ;', [':parent_guid' => $guid]);
        if (count($innerGroups) > 0) 
        {
            foreach($innerGroups as $iG)
            {
                $array[$iG['self_guid']]['name'] = $iG['name'];
                $array[$iG['self_guid']]['is_blocked'] = $iG['is_blocked'];
                $array[$iG['self_guid']]['self_guid'] = $iG['self_guid'];
                $array[$iG['self_guid']]['parent_guid'] = $iG['parent_guid'];
                $array[$iG['self_guid']]['Groups'] = Group::getInnerGroup($iG['self_guid']);    
                $array[$iG['self_guid']]['Machines'] = Machine::getInnerMachines($iG['self_guid']);
            }
        }
        if(count($array) > 0)
        {
            return $array;
        }

        return [];
    }

    public static function setBlockStatus($selfId, $st)
    {
        // добавить / удалить в таблице groupsBlock заблокированную группу
        
        // $db = Db::getInstance();
        // $result = $db->query('SELECT * FROM `groupsBlock` WHERE self_guid = :selfGuid ;', [':selfGuid' => $selfId], static::class);
        // if(count($result[0])>0)
        // {
        //     $result = $db->query('UPDATE `groupsBlock` SET WHERE self_guid = :selfGuid;', [':selfGuid' => $selfId], static::class);
        // }
        $group = self::getBySelfId($selfId);
        $group->setBlocked((int)$st);
        $group->save();
    }
    // public static function saveBlockGroup()
    // {
    //     $db = Db::getInstance();
    //     $result = $db->queryAssoc('SELECT * FROM `groups` WHERE is_blocked = 1;', []);
    //     $sql = 'TRUNCATE TABLE groupsBlock;';
    //     $resultTrancate = $db->query($sql);

    //     foreach($result as $blockGroup)
    //     {
    //         $sql .= 'INSERT INTO groupsBlock ("self_guid") VALUES ('.$blockGroup["self_guid"].')';
    //     }
    //     $db->query($sql);
    // }
}
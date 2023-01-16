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
        return $this->name;
    }

    public function getIsBlocked()
    {
        return $this->isBlocked;
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
    }



    public static function getInnerGroup($guid)
    {
        // var_dump($guid);
        $array = null;
        $db = Db::getInstance();
        $innerGroups = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE parent_guid = :parent_guid ;', [':parent_guid' => $guid]);
        $isRoot = $db->queryAssoc('SELECT parent_guid FROM `'.static::getTableName().'` WHERE self_guid = :guid ;', [':guid' => $guid]);
        if (count($innerGroups) < 1 && $isRoot[0]['parent_guid'] == NULL) {
            $innerGroups = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE self_guid = :guid ;', [':guid' => $guid]);
            foreach($innerGroups as $iG)
            {    
                $array[$iG['self_guid']]['name'] = $iG['name'];
                $array[$iG['self_guid']]['is_blocked'] = $iG['is_blocked'];
                $array[$iG['self_guid']]['self_guid'] = $iG['self_guid'];
                $array[$iG['self_guid']]['parent_guid'] = $iG['parent_guid'];
                $array[$iG['self_guid']]['Machines'] = Machine::getInnerMachines($iG['self_guid']);
            }
        } else {

                foreach($innerGroups as $iG)
                {
                    $array[$iG['self_guid']]['name'] = $iG['name'];
                    $array[$iG['self_guid']]['is_blocked'] = $iG['is_blocked'];
                    $array[$iG['self_guid']]['self_guid'] = $iG['self_guid'];
                    $array[$iG['self_guid']]['parent_guid'] = $iG['parent_guid'];
                    $array[$iG['self_guid']]['Groups'] = Group::getInnerGroup($iG['self_guid']);    
                    $array[$iG['self_guid']]['Machines'] = Machine::getInnerMachines($iG['self_guid']);
                }
                if ($isRoot[0]['parent_guid'] == NULL) {
                    $array[$guid]['name'] = '';
                    $array[$guid]['is_blocked'] = 0;
                    $array[$guid]['self_guid'] = $guid;
                    $array[$guid]['parent_guid'] = NULL;
                    $array[$guid]['Machines'] = Machine::getInnerMachines($guid);
                }

        }
        // else if(){
        //     foreach($innerGroups as $iG)
        //     {
        //         $array[$iG['self_guid']]['name'] = $iG['name'];
        //         $array[$iG['self_guid']]['is_blocked'] = $iG['is_blocked'];
        //         $array[$iG['self_guid']]['self_guid'] = $iG['self_guid'];
        //         $array[$iG['self_guid']]['parent_guid'] = $iG['parent_guid'];
        //         $array[$iG['self_guid']]['Groups'] = Group::getInnerGroup($iG['self_guid']);    
        //         $array[$iG['self_guid']]['Machines'] = Machine::getInnerMachines($iG['self_guid']);
        //     }

        // }
        if(count($array) > 0)
        {
            return $array;
        }
       
        return [];
    }

    public static function setBlockStatus($selfId, $st)
    {
        $group = self::getBySelfId($selfId);
        $group->setBlocked((int)$st);
        $group->save();
    }

    public static function getCountActionGroup()
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'` WHERE is_blocked IS not true;',[]);
        return $result[0]->count;
    }
}

<?php
namespace Gtm\Models\Groups;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;

class Group extends ActiveRecordEntity
{
        /** @var string*/
        protected $name;

            /** @var string*/
        protected $selfGuid;

        /** @var string*/
        protected $parentGuid;

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
    

    public function getName()
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

    public static function saveToBase($groupsArray)
    {
        foreach($groupsArray as $groupArr)
        {
            $group = new Group();
            $group->setName($groupArr['Name']);
            $group->setSelfGuid($groupArr['ID']);
            $group->setParentGuid($groupArr['ParentID']);
            $group->save();
        }
    }

    public static function getGroupsTree()
    {
        $groupTREE = [];
        $depth = 0;
        $db = Db::getInstance();  
        $rootGuid = Company::getById(1)->getRootGuid();
        $allGroupArr = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'`;', []);

        for ($i=0; $i < count($allGroupArr); $i++) { 
            $thisGroupArr = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE parent_guid = :parent_guid ;', [':parent_guid' => $allGroupArr[$i]['self_guid']]);
            if ($depth < count($thisGroupArr)) {
                $depth = count($thisGroupArr);
            }
        }
   
        $groupsTree[$rootGuid]['Groups'] = null;
        $groupsTree[$rootGuid]['Machines'] = null;
        $resultArr = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE parent_guid = :parent_guid ;', [':parent_guid' => $rootGuid]);
        $rootGroupName = Company::getById(1)->getName() != ''?Company::getById(1)->getName():'Наименование компании';
        $groupMenu = "<span class='groupMenuL_0' id='groupLevel_0_$rootGuid'>".$rootGroupName;
        $level_1 = '';
        foreach($resultArr as $group)
        {
            $groupsTree[$rootGuid]['Groups'][$group['self_guid']]['Groups'] = null;
            $groupsTree[$rootGuid]['Groups'][$group['self_guid']]['Machines'] = null;
            $level_1 .= "<span class='groupMenuL_1' id='groupLevel_1_".$group['self_guid']."'>".Group::findOneByColumn('self_guid', $group['self_guid'])->getName().'</span>';

        }        
        
        $groupMenu .= $level_1;
        foreach($groupsTree[$rootGuid]['Groups'] as $grK => $grV)
        {
            $groupsTree[$rootGuid]['Groups'][$grK] = Group::getInnerGroup($grK);
        }

        foreach ($groupsTree as $groupId){
            if ($groupId != null) {
                
            }
        }
        $groupMenu .= "</span>";
        return $groupMenu;
        // return $groupsTree;
    }

    private static function getInnerGroup($guid)
    {
        $array = null;
        $db = Db::getInstance();
        $innerGroups = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` WHERE parent_guid = :parent_guid ;', [':parent_guid' => $guid]);
        if (count($innerGroups) > 0) 
        {
            foreach($innerGroups as $iG)
            {
                $array['Groups'][$iG['self_guid']] = self::getInnerGroup($iG['self_guid']);
                $array['Machines'] = null;
            }
        } else {
            $array['Groups'] = null;
            $array['Machines'] = null;
        }

        return $array;
    }

}
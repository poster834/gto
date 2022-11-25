<?php
namespace Gtm\Models\Schemas;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;
use Gtm\Models\Groups\Group;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Users\UsersAuthService;

class Schema extends ActiveRecordEntity
{
    public static function getTableName()
    {
        return 'schema';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public static function getSchemaTree()
    {
        $db = Db::getInstance();  
        $rootGuid = Company::getById(1)->getRootGuid();
        $user = UsersAuthService::getUserByToken();
        if ($user->isAdmin()) {
            $menu = '<div class="menu" id="menu"><span id="g_'.
        $rootGuid.'" class="menu_G_L_0" id="g_'.$rootGuid.
        '"><span class="pm" id="pm_'.$rootGuid.'"><i class="fa fa-minus" aria-hidden="true"></i></span>'.Company::getById(1)->getName(); $menu .= '</span>';
        } else {  
            $menu = '<div class="menu" id="menu"><span id="g_'.
        $rootGuid.'" class="menu_G_L_0" id="g_'.$rootGuid.
        '" onclick="showGroup(this.id,this)"><span class="pm" id="pm_'.$rootGuid.'"><i class="fa fa-plus-square" aria-hidden="true"></i></span>'.Company::getById(1)->getName(); $menu .= '</span>';
        }
        
        $arrayGroupTree = Group::getInnerGroup($rootGuid);
        
        $menu .= self::getMenu($arrayGroupTree, 1);
       
        $menu .= '</div>';
        return ($menu);
    }



    private static function getMenu($arrayGroupTree, $level)
    { 
        $section = '';
        $classActive = "activeForUser";
        $user = UsersAuthService::getUserByToken();
        if ($user->isAdmin()) {
            foreach($arrayGroupTree as $groupElem)
                {
                    if($groupElem['is_blocked'] == "1")
                    {          
                        $classActive = "notActiveForUser";
                    }
                    $section .= '<span id="g_'.$groupElem['self_guid'].'" parentguid="g_'.$groupElem['parent_guid'].'" class="adminMenuGroup '.$classActive.'" level='.$level.' style="margin-left:'.($level*10).'px;" onclick="changeActiveClass(this.id)">'.$groupElem['name'].'</span>';
                    $classActive = "activeForUser";
                    $level++;
                        foreach($groupElem['Groups'] as $elem)
                        {   
                            $section .= self::getMenu([$elem], $level);
                        } 
                        $level--;
                }
        } else {
            foreach($arrayGroupTree as $groupElem)
                {
                    if(!$groupElem['is_blocked'] == "1")
                    {          
                        $section .= '<span id="g_'.$groupElem['self_guid'].'" parentguid="g_'.$groupElem['parent_guid'].'" class="menu_G_L noVisible" level='.$level.' style="margin-left:'.($level*10).'px;" onclick="showGroup(this.id,this)"><span class="pm" id="pm_'.$groupElem['self_guid'].'"><i class="fa fa-plus-square" aria-hidden="true"></i></span>'.$groupElem['name'].'</span>';
                        foreach($groupElem['Machines'] as $machine)
                        {
                            $section .= '<span id="m_'.$machine['uid'].'"class="menu_G menuMachine noVisible" parentguid="g_'.$machine['guid'].'" style="margin-left:'.(($level*10)+10).'px;" onclick="showMachine(this.id,this)">'.$machine['name'].'</span>';
                        }
                        $level++;
                        foreach($groupElem['Groups'] as $elem)
                        {   
                            $section .= self::getMenu([$elem], $level);
                        } 
                        $level--;
                    }
                  
                }
        }
        return $section;
    }

    // private static function getMenuSection($arr)
    // {
    //     $section = '';
        
           
    //     return $section;
    // }
}
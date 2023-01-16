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
        // $db = Db::getInstance();  
        $rootGuid = Company::getById(1)->getRootGuid();
        $user = UsersAuthService::getUserByToken();
        if ($user->isAdmin() && $_GET['route']=='schema/') {
            // var_dump($_GET);
            $menu = '<div class="menu" id="menu"><span id="g_'.
        $rootGuid.'" class="menu_G_L_0" id="g_'.$rootGuid.
        '"><span class="pm" id="pm_'.$rootGuid.'"><i class="fa fa-minus" aria-hidden="true"></i></span>'.Company::getById(1)->getName(); $menu .= '</span>';
        } else {  
            $menu = '
            <div style="position: relative;">
            <input class="form-control" type="text" name="search" id="search">
            <span id="closeSearchBtn" onclick="clearSearch()">[X]</span>
            </div>            
            <hr>
            <div class="menu" id="menu"><span id="g_'.
        $rootGuid.'" class="menu_G_L_0" id="g_'.$rootGuid.
        '" onclick="showGroup(this.id,this)"><span class="pm" id="pm_'.$rootGuid.'"><i class="fa fa-plus-square" aria-hidden="true"></i></span>'.Company::getById(1)->getName(); $menu .= '</span>';
        }
        
        $arrayGroupTree = Group::getInnerGroup($rootGuid);
        
        $menu .= self::getMenu($arrayGroupTree, 1);
       
        $menu .= '</div>';
        return ($menu);
    }

    public static function getGeoSchemaTree()
    {
        
      
    }

    private static function getMenu($arrayGroupTree, $level)
    { 
        $section = '';
        $classActive = "activeForUser";
        $user = UsersAuthService::getUserByToken();
        if ($user->isAdmin() && $_GET['route']=='schema/') {
            foreach($arrayGroupTree as $groupElem)
                {
                    if($groupElem['is_blocked'] == "1")
                    {          
                        $classActive = "notActiveForUser";
                    }
                    $section .= '<span id="g_'.$groupElem['self_guid'].'" parentguid="g_'.$groupElem['parent_guid'].'" class="adminMenuGroup '.$classActive.'" level='.$level.' style="margin-left:'.($level*7).'px;" onclick="changeActiveClass(this.id)">'.$groupElem['name'].'</span>';
                    $classActive = "activeForUser";
                    $level++;
                    if (isset($groupElem['Groups'])) {
                          foreach($groupElem['Groups'] as $elem)
                            {   
                                $section .= self::getMenu([$elem], $level);
                            } 
                    }
                        $level--;
                }
        } else {
            foreach($arrayGroupTree as $groupElem)
                {
                    if( $groupElem['is_blocked'] <> "1" )
                    {          
                        $section .= '<span id="g_'.$groupElem['self_guid'].'" parentguid="g_'.$groupElem['parent_guid'].'" class="menu_G_L noVisible" level='.$level.' style="margin-left:'.($level*7).'px;" onclick="showGroup(this.id,this)"><span class="pm" id="pm_'.$groupElem['self_guid'].'"><i class="fa fa-plus-square" aria-hidden="true"></i></span>'.$groupElem['name'].'</span>';

                        $level++;
                        if (isset($groupElem['Groups'])) {
                            foreach($groupElem['Groups'] as $elem)
                            {   
                                $section .= self::getMenu([$elem], $level);
                            }     
                        }
                        
                        //сортировка машин по алфавиту
                        usort($groupElem['Machines'], function($a, $b){
                            return ($a['name'] > $b['name']);
                        });

                        foreach($groupElem['Machines'] as $machine)
                        {
                            // $mach = Machine::findOneByColumn('uid', $machine['uid']);
                            // $machSerial = $mach->getSerial();
                            $section .= '<span id="m_'.$machine['uid'].'"class="menu_G menuMachine noVisible" parentguid="g_'.$machine['guid'].'" style="margin-left:'.(($level*7)+7).'px;" onclick="showMachine(this.id)">'.$machine['name'].'</span>';
                        }
                        $level--;
                    }
                  
                }
        }
        return $section;
    }
}
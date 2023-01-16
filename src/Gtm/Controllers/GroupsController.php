<?php
namespace Gtm\Controllers;

use Gtm\Models\Groups\GeoGroup;
use Gtm\Models\Groups\Group;
use Gtm\Models\Schemas\GeoSchema;

ini_set('display_errors',1);
error_reporting(E_ALL);
class GroupsController extends AbstractController
{
    public function activateGroup($activate, $self_id)
    {
        //записать в группу и в таблицу блокированных групп groupBlock
        Group::setBlockStatus($self_id, (int)$activate);
    }

    public function selectGroup($name, $value)
    {
       GeoSchema::setParamByName($name, $value);        
    }

    
    
}
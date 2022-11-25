<?php
namespace Gtm\Controllers;
use Gtm\Models\Groups\Group;

ini_set('display_errors',1);
error_reporting(E_ALL);
class GroupsController extends AbstractController
{
public function activateGroup($activate, $self_id)
{
    //записать в группу и в таблицу блокированных групп groupBlock
    Group::setBlockStatus($self_id, (int)$activate);
    var_dump($activate);
    var_dump($self_id);
}
}
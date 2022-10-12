<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Machines\Machine;

ini_set('display_errors',1);
error_reporting(E_ALL);
class MachinesController extends AbstractController
{
    public function groupsUnion($pageNumber)
    {
        var_dump($pageNumber);
    }

    public function ownersUnion($pageNumber)
    {
        var_dump($pageNumber);
    }

    public function gpsFailuresUnion($pageNumber)
    {
        var_dump($pageNumber);
    }

    public function offensesUnion($pageNumber)
    {
        var_dump($pageNumber);
    }


}
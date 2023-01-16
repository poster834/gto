<?php
namespace Gtm\Controllers;

use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Parkings\Parking;
use Gtm\Models\Users\UsersAuthService;

// ini_set('display_errors',1);
// error_reporting(E_ALL);
class ParkingsController extends AbstractController
{
    public function inParking($uid)
    {
        $parking = new Parking();
        $parking->setUid($uid);
        $parking->setUserId(UsersAuthService::getUserByToken()->getId());
        $parking->setDateStop(date('Y-m-d'));
        $parking->save();
    }

    public function outParking($uid)
    {
        $parking = Parking::getActiveParking($uid);
        $parking->setDateStart(date('Y-m-d'));
        $parking->save();
    }

    public function setParkingReason($uid, $reason)
    {
        $parking = Parking::getActiveParking($uid);
        $parking->setParkingReason($reason); //ENUM 'temporary_driver','conservation','repair','reserve'
        $parking->save();
    }

    public function setParkingComment($uid, $comment)
    {
        $parking = Parking::getActiveParking($uid);
        $parking->setComment($comment);
        $parking->save();
    }
}
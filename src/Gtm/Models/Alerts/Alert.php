<?php
namespace Gtm\Models\Alerts;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;

class Alert extends ActiveRecordEntity
{

/** @var string*/
/* enum('failures', 'offenses') */
protected $type;

/** @var int*/
protected $objectId;

/** @var int*/
protected $recipientId;

/** @var int*/
protected $senderId;

/** @var string*/
protected $messageTxt;

/** @var string*/
protected $messageDate;

public function getType()
{
    return $this->type;
}
public function setType($Type)
{
    $this->type = $Type;
}

public function getObjectId()
{
    return $this->objectId;
}
public function setObjectId($ObjectId)
{
    $this->objectId = $ObjectId;
}

public function getRecipientId()
{
    return $this->recipientId;
}
public function setRecipientId($RecipientId)
{
    $this->recipientId = $RecipientId;
}

public function getSenderId()
{
    return $this->senderId;
}
public function setSenderId($SenderId)
{
    $this->senderId = $SenderId;
}

public function getMessageTxt()
{
    return $this->messageTxt;
}
public function setMessageTxt($MessageTxt)
{
    $this->messageTxt = $MessageTxt;
}

public function getMessageDate()
{
    return $this->messageDate;
}
public function setMessageDate($MessageDate)
{
    $this->messageDate = $MessageDate;
}


    public static function getTableName()
    {
        return 'alerts';
    }

    public static function getCountPerPage()
    {
        return 5;
    }

    public static function testUniqMessage($recipientId, $failureId, $type)
    {
        $db = Db::getInstance();
        $failures = $db->queryAssoc('SELECT * FROM `'.self::getTableName().'` WHERE type = :type && recipient_id = :recipientId && object_id = :failureId;', 
        [':recipientId' => $recipientId, ':failureId' => $failureId, ':type' => $type]);
        
        if (count($failures)>0) {
            return $failures[0]['id'];
        } else return [];
    }

    public function sendAlertEmailToRecipient($id)
    {
        
    }

}
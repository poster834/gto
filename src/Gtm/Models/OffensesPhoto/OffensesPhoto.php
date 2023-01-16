<?php
namespace Gtm\Models\OffensesPhoto;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;

class OffensesPhoto extends ActiveRecordEntity
{

    /** @var string*/
    protected $url;

    /** @var int*/
    protected $offensesId;
    
    protected static function getTableName()
    {
        return 'offensesPhoto';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($Url)
    {
        $this->url = $Url;
    }

    public function getOffensesId()
    {
        return $this->offensesId;
    }

    public function setOffensesId($OffensesId)
    {
        $this->offensesId = $OffensesId;
    }

    public function getPhotoByFailuresId($id)
    {
        $db = Db::getInstance();
        $photo = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE offenses_id = :id ;', [':id' => $id]);
        return $photo;
    }


    public function updateFromArray(array $fields)
    {

    }


}
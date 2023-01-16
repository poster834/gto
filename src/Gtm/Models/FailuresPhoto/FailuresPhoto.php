<?php
namespace Gtm\Models\FailuresPhoto;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;

class FailuresPhoto extends ActiveRecordEntity
{

    /** @var string*/
    protected $url;

    /** @var int*/
    protected $failuresId;
    
    protected static function getTableName()
    {
        return 'failuresPhoto';
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

    public function getFailuresId()
    {
        return $this->failuresId;
    }

    public function setFailuresId($FailuresId)
    {
        $this->failuresId = $FailuresId;
    }

    public static function getPhotoByFailuresId($id)
    {
        $db = Db::getInstance();
        $photo = $db->query('SELECT * FROM `'.static::getTableName().'` WHERE failures_id = :id ;', [':id' => $id], self::class);
        return $photo;
    }


    public function updateFromArray(array $fields)
    {

    }

    public static function deleteByFailuresId($id)
    {
        $photos = self::getPhotoByFailuresId($id);

        foreach($photos as $photo)
        {
            $photo->deletePhoto($photo->getId());
        }
    }

    
    public static function deletePhoto($id)
    {
        $photo = self::getById($id);
        $url = $photo->getUrl();
        $path = $_SERVER["DOCUMENT_ROOT"].'/gtm/photo/';

        $path .= $url;
        unlink($path);
        var_dump($photo->delete());
    }


}
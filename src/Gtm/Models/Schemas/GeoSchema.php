<?php
namespace Gtm\Models\Schemas;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Services\Db;
use Gtm\Models\Companys\Company;
use Gtm\Models\Groups\Group;
use Gtm\Models\Users\UsersAuthService;

class GeoSchema
{
    public static function getTableName()
    {
        return 'geoSchemaParams';
    }

    public static function getCountPerPage()
    {
        return 5;
    }


    public static function getParamByName($name)
    {
        $db = Db::getInstance();
        $value = $db->queryAssoc('SELECT value FROM `'.static::getTableName().'` WHERE name = :name;', [':name' => $name]);
        if(count($value) > 0)
        {
            return $value[0]['value'];
        } else {
            return null;
        }
    }

    public static function setParamByName($name, $value)
    {
        $params = [];
        $param = self::getParamByName($name);
        if ($param == null) {
            $sql = 'INSERT INTO ' . static::getTableName() . '(`name`,`value`) VALUES (\''. $name . '\' , \''. $value . '\')';
        } else {
            $sql = 'UPDATE ' . static::getTableName() . ' SET `value` = \''.$value.'\' WHERE `name` = \''.$name.'\'';
        }

        $db = Db::getInstance();
        $db->query($sql, $params, static::class);
    }

    public static function getAllParams()
    {
        $db = Db::getInstance();
        $value = $db->queryAssoc('SELECT * FROM `'.static::getTableName().'` ', []);
        if(count($value) > 0)
        {
            return $value;
        } else {
            return null;
        }
    }

 
}
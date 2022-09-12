<?php
namespace Gtm\Models\Roles;

use \Gtm\Models\ActiveRecordEntity;
use \Gtm\Exceptions\InvalidArgumentException;

class Role extends ActiveRecordEntity
{

    /** @var string*/
    public $name;

    /** @var string*/
    protected $description;
    
   protected static function getTableName()
    {
        return 'roles';
    }

    public static function getRolesName()
    {
        return self::$name;
    }

    public function getRoleDescription()
    {
        return $this->description;
    }
}
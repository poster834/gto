<?php
namespace Gtm\Models\Roles;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class Role extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;

    /** @var string*/
    protected $description;
    
    protected static function getTableName()
    {
        return 'roles';
    }
    
    protected static function getCountPerPage()
    {
        return 10;
    }

    public function getRoleName()
    {
        return $this->name;
    }

    public function getRoleDescription()
    {
        return $this->description;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setDescription($newDescription)
    {
        $this->description = $newDescription;
    }

    public function updateFromArray(array $fields):Role
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано имя роли");
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException("Нет указано описание роли");
        }

        $this->setName($fields['name']);
        $this->setDescription($fields['description']);
        $this->save();
        return $this;
    }


}
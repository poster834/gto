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

    /** @var int*/
    protected $editable;
    
    
    protected static function getTableName()
    {
        return 'roles';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
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



    public function getEditable()
    {
        return $this->editable;
    }

    public function setEditable($Editable)
    {
        $this->editable = $Editable;
    }


    public function updateFromArray(array $fields)
    {
        $testUni = Role::uniquenessColumnTest($fields,'name');

        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: Имя");
        }
        if (empty($fields['description'])) {
            throw new InvalidArgumentException("Не указано поле: Описание");
        }
        if (!$testUni) {
            throw new InvalidArgumentException("Значение поля 'Имя:' не уникально");
        }
        
        $this->setName($fields['name']);
        $this->setDescription($fields['description']);
        $this->setEditable(1);
        $this->save();
        return $this;
    }
}
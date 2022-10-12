<?php
namespace Gtm\Models\FailuresTypes;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class FailuresType extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;
    
    
    protected static function getTableName()
    {
        return 'failuresType';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function updateFromArray(array $fields)
    {
        $testUni = FailuresType::uniquenessColumnTest($fields,'name');

        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: Имя");
        }
   
        if (!$testUni) {
            throw new InvalidArgumentException("Значение поля 'Имя:' не уникально");
        }
        
        $this->setName($fields['name']);
        $this->save();
        return $this;
    }
}
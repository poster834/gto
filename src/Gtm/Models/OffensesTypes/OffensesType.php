<?php
namespace Gtm\Models\OffensesTypes;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;


class OffensesType extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;
    
    
    protected static function getTableName()
    {
        return 'offensesType';
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
        // var_dump($fields);
        $testUni = OffensesType::uniquenessColumnTest($fields,'name');

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
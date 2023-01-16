<?php
namespace Gtm\Models\Directions;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Services\Db;

class Direction extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;
    
    
    protected static function getTableName()
    {
        return 'directions';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRegionsCountInDirection()
    {
        $id = $this->getId();
            $db = Db::getInstance();
            $result = $db->query('SELECT COUNT(*) as count FROM `regions` WHERE direction=:id;',[':id' => $id]);
            return $result[0]->count;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function updateFromArray(array $fields)
    {
        $testUni = Direction::uniquenessColumnTest($fields,'name');

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
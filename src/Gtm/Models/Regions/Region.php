<?php
namespace Gtm\Models\Regions;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\Directions\Direction;

class Region extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;
    
    /** @var string*/
    protected $direction;
	
    
    protected static function getTableName()
    {
        return 'regions';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDirectionId()
    {
        return $this->direction;
    }
    public function getDirectionName()
    {
        return Direction::getById($this->direction)->getName();
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setDirection($newDirection)
    {
        $this->direction = $newDirection;
    }

    public function updateFromArray(array $fields)
    {
        $testUni = Region::uniquenessColumnTest($fields,'name');

        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: Имя");
        }
        if (empty($fields['direction']) || $fields['direction'] == 'null') {
            throw new InvalidArgumentException("Не указано поле: Направление");
        }
        if (!$testUni) {
            throw new InvalidArgumentException("Значение поля 'Имя:' не уникально");
        }
        
        $this->setName($fields['name']);
        $this->setDirection($fields['direction']);
        $this->save();
        return $this;
    }
}
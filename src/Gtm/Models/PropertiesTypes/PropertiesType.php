<?php
namespace Gtm\Models\PropertiesTypes;

use Gtm\Models\ActiveRecordEntity;
use Gtm\Exceptions\InvalidArgumentException;


class PropertiesType extends ActiveRecordEntity
{

    /** @var string*/
    protected $name;

    /** @var string*/
    protected $description;
    
    /** @var int*/
    protected $sort;

    /** @var int*/
    protected $isUses;
    
    protected static function getTableName()
    {
        return 'propertiesType';
    }
    
    protected static function getCountPerPage()
    {
        return 5;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSort()
    {
        return $this->sort;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getIsUses()
    {
        return $this->isUses;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function setSort($newSort)
    {
        $this->sort = $newSort;
    }

    public function setIsUses($newIsUses)
    {
        $this->isUses = $newIsUses;
    }

    public function setDescription($newDescription)
    {
        $this->description = $newDescription;
    }

    public function updateFromArray(array $fields)
    {
        // $testUni = PropertiesType::uniquenessColumnTest($fields,'name');

        if (empty($fields['name'])) {
            throw new InvalidArgumentException("Не указано поле: Имя");
        }

        if (empty($fields['description'])) {
            throw new InvalidArgumentException("Не указано поле: Название в карточке");
        }

        if (empty($fields['sort'])) {
            throw new InvalidArgumentException("Не указано поле: Порядок сортировки");
        }
   
        // if (!$testUni) {
        //     throw new InvalidArgumentException("Значение поля 'Имя:' не уникально");
        // }
        $this->setName($fields['name']);
        $this->setDescription($fields['description']);
        $this->setSort((int) $fields['sort']);
        $this->setIsUses(1);
        $this->save();
        return $this;
    }

    public static function saveToBase($propertiesTypes, $basic)
    {
        //собираем все параметры в таблице и присваиваем им сортировку 0
        $propertiesTypesObj = PropertiesType::findAll();
        foreach($propertiesTypesObj as $propertiesType)
            {
                $propertiesType->setSort(0);
                $propertiesType->save();
            }
        
        foreach($propertiesTypes as $key => $count) //перебираем массив параметров из схемы 
        {
            $propertiesType = PropertiesType::findOneByColumn('name', $key);
            if ($propertiesType == null) { //если такой параметр не найден добавляем иначе просто обновляем его данные
                $propertiesType = new PropertiesType();
                $propertiesType->setName($key);
                $propertiesType->setSort($count);
                $propertiesType->save();
            } else {
                $propertiesType->setSort($count);
                $propertiesType->save();
            }
        }
    
        //собираем все параметры в таблице с сортировкой 0 и удаляем их
        $propertiesTypesObj = PropertiesType::findAll();
        foreach($propertiesTypesObj as $propertiesType)
            {
                if($propertiesType->getSort() == 0)
                {
                    $propertiesType->delete();
                }   
            }
        
        
    }
}
<?php
namespace Gtm\Models;

use Gtm\Services\Db;

abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source)
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    public static function findAll()
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `'.static::getTableName().'`;', [], static::class);
    }

    public static function findAllWhithPaginations($countPerPage)
    {
        $db = Db::getInstance();
        $allItems = $db->query('SELECT * FROM `'.static::getTableName().'`;', [], static::class);
        // return $allItems;

        for ($i=0; $i < count($allItems); $i++) { 
            echo $allItems[$i];
        }
    }



    public static function getById(int $id)
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `'.static::getTableName().'` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public static function findOneByColumn($columnName, $value)
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `'.static::getTableName(). '` WHERE `'.$columnName.'` =:value LIMIT 1;',
            [':value'=>$value],
            static::class
        );
        if ($result === []) {
            return null;
        }
        return $result[0];
    }

    private function mapPropertiesToDbFormat()
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

private function camelCaseToUnderscore(string $source)
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
}

public function save()
{
    $mappedProperties = $this->mapPropertiesToDbFormat();
    if ($this->id !== null) {
        $this->update($mappedProperties);
    } else {
        $this->insert($mappedProperties);
    }
}

private function update(array $mappedProperties)
{
    $columns2params = [];
    $params2values = [];
    $index = 1;
    foreach ($mappedProperties as $column => $value) {
        $param = ':param' . $index; // :param1
        $columns2params[] = $column . ' = ' . $param; // column1 = :param1
        $params2values[$param] = $value; // [:param1 => value1]
        $index++;
    }
    $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
    $db = Db::getInstance();
    $db->query($sql, $params2values, static::class);
}

private function insert(array $mappedProperties)
{
        $mappedPropertiesNotNull = array_filter($mappedProperties);

        $columns = [];
        $params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedPropertiesNotNull as $column => $value) {
            $params[] = ':param' . $index; // :params
            $columns[] = $column; // columns
            $params2values[':param' . $index] = $value; // [:param => value]
            $index++;
        }

        $sql = 'INSERT INTO ' . static::getTableName() . '(' . implode(', ', $columns) . ') VALUES (' . implode(', ', $params) . ')';
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    private function refresh()
    {
        $objectFromDb = static::getById($this->id);
        foreach ($objectFromDb as $property => $value) {
            $this->$property = $value;
        }
    }

    public function delete()
    {
        $sql = 'DELETE FROM '. static::getTableName() . ' WHERE id = :id';
        $db = Db::getInstance();
        $db->query($sql, [':id' => $this->id], static::class);
        $this->id = null;
    }

    public static function findLastAddedId()
    {
        $sql = 'SELECT * FROM '. static::getTableName() . ' ORDER BY id DESC LIMIT 1;';
        $db = Db::getInstance();
        $result = $db->query($sql, [], static::class);

        if ($result === []) {
            return null;
        }
        return $result[0]->getId();
    }

    public static function findAllPerPage($pageNumber)
    {
        $countPerPage = static::getCountPerPage();
        // $countPerPage = 10;
        $startRow = ($pageNumber * $countPerPage)-$countPerPage;
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `'.static::getTableName().'` LIMIT '.$startRow.','.$countPerPage.';', [], static::class);
        return $result;
    }

    public static function getPagesPaginator()
    {
        $countPerPage = static::getCountPerPage();
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'`;',[]);
        return ceil((int)($result[0]->count) / $countPerPage);
    }

    public static function getLastPage()
    {
        $countPerPage = static::getCountPerPage();
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'`;',[]);
        return ceil((int)($result[0]->count) / $countPerPage);
    }

    public static function getActivePageById($id)
    {
        $countPerPage = static::getCountPerPage();
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) as count FROM `'.static::getTableName().'` WHERE id < :id;',[':id'=>$id]);
        $row = (int)($result[0]->count)+1;
        $p = (int)ceil($row / $countPerPage);
        return $p;
    }

    public static function uniquenessColumnTest($fields, $columnName)
    {   
        $db = Db::getInstance();
        if (empty($fields['id'])) {
            $id = 0;
        } else {
            $id = $fields['id'];
        }
        $sql = 'SELECT * FROM '. static::getTableName() . ' WHERE `'.$columnName.'` = :value &&  `id` <> :id LIMIT 1;';
        $result = $db->query($sql, [':value' => $fields[$columnName], ':id'=>$id], static::class);
        return (empty($result));
    }

    abstract protected static function getTableName();
    abstract protected static function getCountPerPage();

}
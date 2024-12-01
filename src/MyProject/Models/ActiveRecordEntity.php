<?php

namespace MyProject\Models;

use MyProject\Services\Db;

/**
 * Summary of ActiveRecordEntity
 */

abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @method __set -  магическим методом, который используется
     * для обработки попыток установить значения недоступных или
     * несуществующих свойств объекта. Этот метод позволяет вам управлять
     * поведением объекта при попытке присвоить значение свойству,
     * которое не было объявлено или доступно.
     * @param string $name -  имя свойства, которое вы пытаетесь установить.
     * @param  $value  — значение, которое вы пытаетесь присвоить этому свойству.
     * @return string
     */

    public function __set(string $name, $value): void
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    /**
     * @method getById - получат информацию по id
     * @param int $id - id
     * @return ?self
     */

    public static function getById(int $id): ?self
    {

        $db = $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    /**
     * @method underscoreToCamelCase - переписывает названия столбцов базы данных для взаимодействия
     * @param string $source
     * @return string
     */

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @method save() - сохраняет данные или обновляет данные
     * @return void
     */

    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();

        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    /**
     * @method delete() - удаляет данные
     * @return bool
     */

    public function delete(): bool
    {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM `' . static::getTableName() . '` WHERE id = :id',
            [':id' => $this->id]
        );
        $this->id = null;

        return true;
    }

    /**
     * @method update() - обновляет данные
     * @param array $mappedProperties
     * @return void
     */

    private function update(array $mappedProperties): void
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

    /**
     * @method insert() - добавляет данные
     * @param array $mappedProperties
     * @return void
     */

    private function insert(array $mappedProperties): void
    {
        $filteredProperties = array_filter($mappedProperties);

        $columns = [];
        $paramsNames = [];
        $params2values = [];
        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`' . $columnName . '`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $params2values[$paramName] = $value;
        }

        $columnsViaSemicolon = implode(', ', $columns);
        $paramsNamesViaSemicolon = implode(', ', $paramsNames);

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') VALUES (' . $paramsNamesViaSemicolon . ');';

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    /**
     * @method refresh() - обновляет данные
     * @return void
     */

    private function refresh(): void
    {
        $objectFromDb = static::getById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
        }
    }

    /**
     * @method  mapPropertiesToDbFormat() - преобразуем данные в нужный формат
     * @return array
     */
    private function mapPropertiesToDbFormat(): array
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

    /**
     * @method  camelCaseToUnderscore() - преобразуем данные в нужный формат
     * @return array
     */
    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', $source));
    }

}

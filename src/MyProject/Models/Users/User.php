<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Services\Db;

class User extends ActiveRecordEntity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $email;

    /** @var int */
    protected $phone;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {

        return $this->phone;
    }

    /**
     * @return void
     */
    public function setName($name): void
    {

        $this->name = $name;
    }

    /**
     * @return void
     */
    public function setEmail($email): void
    {

        $this->email = $email;
    }

    /**
     * @return void
     */
    public function setPhone($phone): void
    {

        $this->phone = $phone;
    }

    /**
     * @method addUser - добавления user
     * @param array $userData - данные user
     * @return User
     */

    public static function addUser(array $userData): User
    {
        if (empty($userData['name'])) {
            throw new InvalidArgumentException('Поле должно быть заполнено');
        }

        if (!preg_match('/^[a-zA-Z]+$/', $userData['name'])) {
            throw new InvalidArgumentException('Имя должно содержать символовы латинского алфавита');
        }

        if (!preg_match('/^\d+$/', $userData['phone'])) {
            throw new InvalidArgumentException('Вы передали неверные данные');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        $user = new User();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->save();

        return $user;
    }

    /**
     * @method getTableName() - получает названия таблицы
     * @return string
     */

    protected static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @method getAllUsers() - получает всех user
     * @return array
     */

    public static function getAllUsers(): array
    {
        $db = $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '` ORDER BY id DESC ;', [], static::class);
    }

    /**
     * @method getInfUserForId() - получает user по id
     * @param int $id - id user
     * @return mixed
     */

    public static function getInfUserForId(int $id): mixed
    {

        $db = Db::getInstance();
        $sql = "SELECT * FROM `users` WHERE id = :id";

        $result = $db->queryForEdit($sql, [':id' => $id]);

        return $result ? $result[0] : '';

    }

    /**
     * @method updateDataUsery() - обновляет user по id
     * @param array $fields
     * @return User
     */

    public function updateDataUsery(array $fields): User
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано имя');
        }

        if (empty($fields['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (empty($fields['phone'])) {
            throw new InvalidArgumentException('Не передан phone');
        }

        if (!preg_match('/^[a-zA-Z]+$/', $fields['name'])) {
            throw new InvalidArgumentException('Имя должно содержать символовы латинского алфавита');
        }

        if (!preg_match('/^\d+$/', $fields['phone'])) {
            throw new InvalidArgumentException('Вы передали неверные данные');
        }

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email некорректен');
        }

        $this->setName($fields['name']);
        $this->setEmail($fields['email']);
        $this->setPhone($fields['phone']);
        $this->save();
        return $this;
    }

}

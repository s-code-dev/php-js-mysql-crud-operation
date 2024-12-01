<?php
namespace MyProject\Services;

use MyProject\Exceptions\DbException;

class Db
{
    /**
     * @var $pdo - подключение к базе данных через pdo
     * */
    private $pdo;
    /**
     * @var $instance - для создания одного подключения к базе данных | singleton
     * */
    private static $instance;

    private function __construct()
    {

        // берем  настройки базы данных mysql и кладем в переменную $dbOptions
        $dbOptions = (require __DIR__ . '/../../settings.php')['db'];

        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password'],
            );
            $this->pdo->exec('SET NAMES UTF8');
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при подключении к базе данных: ' . $e->getMessage());
        }
    }

    /**
     * @method  getInstance() - singleton
     * @return \MyProject\Services\Db
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @method  getLastInsertId() - получить последний вставленный идентификатор
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @method query() - для общего взаимодействия с базой данных
     * @param string $sql - запрос sql
     * @param array $params - передаваемые параметры
     * @param string $className - передаем класс для ORM
     * @return array|null
     */
    public function query(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {

        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @method queryForEdit() - для общего взаимодействия с базой данных | редактирования
     * @param string $sql - запрос sql
     * @param array $params - передаваемые параметры
     * @return array|null
     */
    public function queryForEdit(string $sql, array $params = [], ): array | null
    {

        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

}

<?php
namespace Gtm\Services;

class Db
{
    private static $instanceCount = 0;
    private static $instance;
    private $pdo;

    public static function getInstanceCount()
    {
        return self::$instanceCount;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        self::$instanceCount ++;

        $dbOptions = (require __DIR__.'/../../settings.php')['db'];
        $this->pdo = new \PDO(
            'mysql:host='.$dbOptions['host'].';dbname='.$dbOptions['dbName'],
            $dbOptions['user'],
            $dbOptions['password']
        );
        $this->pdo->exec('SET NAMES UTF8');
    }

    public function query(string $sql, $params = [])
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if ($result === false) {
            return null;
        }
        return $sth->fetchAll();
    }

}
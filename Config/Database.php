<?php

namespace Config;

use Dotenv\Dotenv;

class Database
{
    static $connection;

    /**
     * getConnection
     *
     * @return \PDO
     */
    public static function getConnection()
    {
        if (self::$connection == null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
            self::$connection = new \PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASS']);
            if (!isset($_ENV['ENV_APP']) || $_ENV['ENV_APP'] == "dev") {
                $sql = "SHOW TABLES FROM " . $_ENV['DB_NAME'];
                $stmt = self::$connection->query($sql);
                $result = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                if (!$result || empty($result)) {
                    $sql = @file_get_contents("sql.sql");
                    if ($sql) {
                        self::$connection->query($sql);
                    }
                }
            }
        }
        return self::$connection;
    }

    /**
     * getResults
     *
     * @param  string $sql
     * @param  array $values
     * @throws \Exception
     * @return array|null
     */
    public static function getResults(string $sql, array $values =  [])
    {
        try {
            self::getConnection();
            $retval = self::$connection->prepare($sql);
            if ($retval) {
                $retval->execute($values);
                if ($retval->rowCount() > 0) {
                    return $retval->fetchAll(\PDO::FETCH_OBJ);
                }
                $retval->closeCursor();
                return [];
            }
            return null;
        } catch (\Exception $e) {
            var_dump($e);
            return null;
        }
    }


    /**
     * operation
     *
     * @param  string $sql
     * @param  array $values
     * @throws \Exception
     * @return boolean|int
     */
    public static function operation(string $sql, array $values =  [])
    {
        try {
            self::getConnection();
            $retval = self::$connection->prepare($sql);
            if ($retval) {
                $rs = $retval->execute($values);
                $retval->closeCursor();
                if (!$rs) {
                    return $rs;
                }
                return self::$connection->lastInsertId() ?: true;
            }
            return false;
        } catch (\Exception $e) {
            var_dump($e);
            return false;
        }
    }
}

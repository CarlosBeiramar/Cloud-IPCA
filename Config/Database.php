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
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
            self::$connection = new \PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8', $_ENV['DB_USER'], $_ENV['DB_PASS']);
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
            var_dump( $e->getMessage());die();
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
            var_dump( $e->getMessage());die();
        }
    }
}

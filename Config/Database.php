<?php

namespace Config;

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
            self::$connection = new \PDO('mysql:host=alves-mysql-1;dbname=cloud;charset=utf8', "root", "root");
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
            die();
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
            return false;
        }
    }
}

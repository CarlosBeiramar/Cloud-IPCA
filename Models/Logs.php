<?php

namespace Models;

use Config\Database;

class Logs
{
    public int $idlog;
    public ?int $iduser;
    public ?string $ip;
    public string $resource;
    public string $method;
    public int $success;
    public int $tokenvalidate;
    public string $message;

    public ?string $created_at;
    public ?string $updated_at;



    /**
     * find
     *
     * @param  string $columns
     * @param  array $filters
     * @param  string $order
     * @param  int $limit
     * @return array|null
     */
    public static function find(string $columns = "*", array $filters = [], ?string $order = null, ?int $limit = null)
    {
        $sql = "SELECT " . $columns . " FROM `logs`  ";
        if (!empty($filters)) {
            $sql .= " WHERE ";
            $count = 0;
            foreach ($filters as $column => $value) {
                if ($count > 0) {
                    $sql .= " AND ";
                }
                $sql .= $column . " = :" . $column;
                $count++;
            }
        }
        if ($order !== null) {
            $sql .= " order by " . $order;
        }
        if ($limit !== null) {
            $sql .= " limit " . $limit;
        }
        return Database::getResults($sql, $filters);
    }

    /**
     * insert
     *
     * @return boolean|int
     */
    public function insert()
    {
        $sql = " INSERT INTO `logs`
        (`idlog`, `iduser`, `ip`, `resource`, `success`, `message`, `method`, `tokenvalidate`)
        VALUES
        (null,    :iduser,  :ip,  :resource,  :success,  :message, :method, :tokenvalidate)";
        $values = [
            "iduser" => $this->iduser,
            "ip" => $this->ip,
            "resource" => $this->resource,
            "success" => $this->success,
            "message" => $this->message,
            "method" => $this->method,
            "tokenvalidate" => $this->tokenvalidate
        ];
        return Database::operation($sql, $values);
    }


    /**
     * update
     *
     * @return boolean
     */
    public function update()
    {
        $sql = " UPDATE `logs` SET ";
        $values = [];
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $default_value) {
            if ($this->{$name} !== null) {
                $sql .= $name . " = :" . $name . ",";
                $values[$name] = $this->{$name};
            }
        }
        $sql = substr_replace($sql, "", -1); // remove last char because comma is invalid
        $sql .= " WHERE `idlog` = :idlog";
        $values["idlog"] = $this->idlog;
        return Database::operation($sql, $values);
    }
}

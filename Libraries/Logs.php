<?php

namespace Libraries;

use Models\Logs as LogsModel;

class Logs
{

    static $log;


    /**
     * initialize
     *
     * @param  string $resource
     * @param  string $method
     * @return void
     */
    public static function initialize(string $resource, string $method)
    {

        self::$log = new LogsModel();
        self::$log->iduser = null;
        self::$log->message = "";
        self::$log->resource = $resource;
        self::$log->ip = $_SERVER['REMOTE_ADDR'];
        self::$log->success = 0;
        self::$log->created_at = null;
        self::$log->updated_at = null;
        self::$log->method = $method;
        self::$log->tokenvalidate = 0;
        self::$log->idlog = self::$log->insert();
    }


    /**
     * updateInfo
     *
     * @param  array $info
     * @return void
     */
    public static function updateInfo(array $info = [])
    {
        foreach ($info as $key => $value) {
            self::$log->{$key} = $value;
        }
        self::$log->update();
    }

    /**
     * accessIsInvalid - check if the ip has already failed 5 consecutive times and when was the last time 
     * 
     * @param  string $resource
     * @param  string $method
     * @return int
     */
    public static function accessIsInvalid(string $resource, string $method)
    {
        $logs = LogsModel::find("*", ["resource" => $resource, "method" => $method, "ip" => $_SERVER['REMOTE_ADDR']], "idlog DESC", 5);
        if ($logs && count($logs) == 5) {
            $count = 0;
            foreach ($logs as $key => $value) {
                if ($value->tokenvalidate == 1) {
                    break;
                }
                $count++;
            }
            if ($count == 5) {
                if (($diff_seconds = time() - strtotime($logs[0]->created_at)) < 30) {
                    return 30 - $diff_seconds;
                }
            }
        }
        return 0;
    }
}

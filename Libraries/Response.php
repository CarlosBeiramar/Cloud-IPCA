<?php

namespace Libraries;

class Response
{
    /**
     * sendResponse
     *
     * @param  int $code code http
     * @param  array $result result to send
     * @return void
     */
    public static function sendResponse(int $code, array $result)
    {
        header('Content-Type: application/json; charset=utf-8');
        $message = isset($result["msg"]) ? $result["msg"] : "";
        if ($code == 200 || $code == 205) {
            Logs::updateInfo(["success" => 1, "message" => "Code: ".$code." | ".$message]);
        } else {
            Logs::updateInfo(["success" => 0, "message" => "Code: ".$code." | ".$message]);
        }
        http_response_code($code);
        print json_encode($result);
        die();
    }
}

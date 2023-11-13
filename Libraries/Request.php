<?php

namespace Libraries;

use \Libraries\Encrypt;
use \Libraries\Response;
use \Models\User;

class Request
{
    /**
     * verifyToken
     *
     * @param  array $levels
     * @return User
     */
    public static function verifyToken(array $levels = [])
    {
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
            Response::sendResponse(401, ["msg" => "Authentication not received"]);
        }
        $jwt = Encrypt::decryptJwt($_SERVER['HTTP_AUTHORIZATION']);
        if (!empty($jwt['error'])) {
            Response::sendResponse(503, ["msg" => $jwt['error']]);
        }
        $user = User::find("*", ["apikey" => $jwt['sub']]);
        if (!$user) {
            Response::sendResponse(404, ["msg" => "User not Found"]);
        }
        Logs::updateInfo(["iduser" => $user[0]->iduser, "tokenvalidate" => 1]);
        if (!in_array($user[0]->type, $levels)) {
            Response::sendResponse(405, ["msg" => "Method Not Allowed"]);
        }
        return $user;
    }


    /**
     * getPostParams
     *
     * @return array
     */
    public static function getPostParams()
    {
        if (empty($_POST)) {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        } else {
            return $_POST;
        }
    }
}

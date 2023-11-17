<?php

namespace Controllers;

use Libraries\Response;
use Libraries\Encrypt;
use Models\User;
use Libraries\Logs;

class AuthController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
            Response::sendResponse(401, ["msg" => "Authentication not received"]);
        }
        $ha = base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6));
        list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $ha);
        $auth_pw = Encrypt::encode($_SERVER['PHP_AUTH_PW']);

        $user_class = new User();
        $user_class->name = "Test Unit";
        $user_class->email = $_SERVER['PHP_AUTH_USER'];
        $user_class->type = 0;
        $user_class->apikey = md5(openssl_random_pseudo_bytes(17) . date('Y-m-d H:i:s'));
        $user_class->password = "SlZ1QXBYZEcrY0FGY21mWDNUT2pQdz09";
        $res = $user_class->insert();
        var_dump($res);die();
        $user = User::find("*", ["email" => $_SERVER['PHP_AUTH_USER'], "password" => $auth_pw]);
        if (!$user) {
            Response::sendResponse(401, ["msg" => "User not found"]);
        }
        Logs::updateInfo(["iduser" => $user[0]->iduser, "tokenvalidate" => 1]);
        $jwt = Encrypt::encryptJwt($user[0]->apikey);
        if (!empty($jwt['error'])) {
            Response::sendResponse(503, ["msg" => $jwt['error']]);
        } else {
            Response::sendResponse(200, ["token" => $jwt["token"], "expire" => $jwt["expire"]]);
        }
    }
}

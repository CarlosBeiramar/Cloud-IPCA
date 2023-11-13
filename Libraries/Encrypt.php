<?php

namespace Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Encrypt
{
    private static $secret_key = "aeb0423474cae7bc96d2e7faab13ad30";
    private static $secret_iv = "1af0891441629cc190fe276bc7618841";
    private static $encrypt_method = "AES-256-CBC";
    private static $jwt_secret = "j5bn5bf004j85jch3ycxlo188agm56ui";

    /**
     * encode
     *
     * @param  string $value
     * @return string
     */
    public static function encode(string $value)
    {
        $key = hash('sha256', self::$secret_key);
        $iv = substr(hash('sha256', self::$secret_iv), 0, 16);
        $output = openssl_encrypt($value, self::$encrypt_method, $key, 0, $iv);
        return base64_encode($output);
    }


    /**
     * encryptJwt
     *
     * @param  string $string string to encrypt
     * @throws \Exception
     * @return array
     */
    public static function encryptJwt($string)
    {
        $time = time();
        $exp = $time + 600;
        $payload = [
            'sub' => $string,
            'iss' => $_SERVER['HTTP_HOST'],
            'aud' => $_SERVER['HTTP_USER_AGENT'],
            'iat' => $time,
            'exp' => $exp
        ];
        $headers = [];
        try {
            return ["token" => JWT::encode($payload, self::$jwt_secret, 'HS256', null, $headers), "expire" => $exp];
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    /**
     * decryptJwt
     *
     * @param  string $jwt jwt token to decrypt
     * @throws \Exception
     * @return array
     */
    public static function decryptJwt($jwt)
    {
        try {
            return  (array)JWT::decode($jwt, new Key(self::$jwt_secret, 'HS256'));
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
}

<?php

namespace App;

class Tool
{
    /**
     * Returns a random array item
     *
     * @param array $array
     * @return mixed
     */
    public static function randomItem(array $array): mixed
    {
        if (!empty($array)) {
            return $array[rand(0, count($array) - 1)];
        }
    }

    /**
     * base64 URL encode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_encode(string $data) : string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * base64 URL decode
     *
     * @param string $data
     * @return string
     */
    public static function base64url_decode(string $data) : string
    {
        // return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /**
     * Encrypt encode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function encode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_encode($data);
        } else if($method == 'laravel') { // laravel internal encoder
            return encrypt($data);
        } else {
            return self::base64url_encode($data);
        }
    }
    
    /**
     * Encrypt decode ('base_64' or 'laravel')
     *
     * @param string $data, bool $method = 'base_64'
     * @return string
     */
    public static function decode(string $data, string $method = 'base_64') : string
    {
        if ($method == 'base_64') {
            return self::base64url_decode($data);
        } else if ($method == 'laravel') { // laravel internal encoder
            return decrypt($data);
        } else {
            return self::base64url_decode($data);
        }
    }
}
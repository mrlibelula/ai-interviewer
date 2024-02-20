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
}
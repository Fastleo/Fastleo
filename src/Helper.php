<?php

namespace Fastleo\Fastleo;

class Helper
{
    public static function getName($string)
    {
        $array = explode('/', $string);
        $result = end($array);
        return $result;
    }
}
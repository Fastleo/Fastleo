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

    public static function str2class($string)
    {
        $array = explode('_', $string);
        foreach ($array as $k => $v) {
            if ($k > 0) {
                $array[$k] = ucfirst($v);
            }
        }
        return implode($array);
    }
}
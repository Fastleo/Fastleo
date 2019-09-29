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

    public static function getModels()
    {
        $appModels = [];

        foreach (scandir(base_path('app')) as $file) {

            $pathInfo = pathinfo($file);

            if (isset($pathInfo['extension']) and $pathInfo['extension'] == 'php') {

                if ($pathInfo['filename'] != 'User' and class_exists('App\\' . $pathInfo['filename'])) {

                    $name = 'App\\' . $pathInfo['filename'];
                    $app = new $name();

                    if (isset($app->fastleo) and $app->fastleo == false) {
                        continue;
                    }

                    $appModels[strtolower($pathInfo['filename'])] = [
                        'icon' => $app->fastleo_model['icon'] ?? null,
                        'name' => $app->fastleo_model['name'] ?? $pathInfo['filename'],
                        'title' => $app->fastleo_model['title'] ?? $pathInfo['filename'],
                    ];
                }
            }
        }

        return $appModels;
    }
}
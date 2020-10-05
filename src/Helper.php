<?php

namespace Fastleo\Fastleo;

class Helper
{
    /**
     * Получение последнего значения
     * @param string $string
     * @return string
     */
    public static function getName(string $string): string
    {
        $array = explode('/', $string);
        return end($array);
    }

    /**
     * Преобразования строки в название метода
     * @param string $string
     * @return string
     */
    public static function str2method(string $string): string
    {
        return \Illuminate\Support\Str::camel($string);
    }

    /**
     * Преобразования строки в название класса
     * @param string $string
     * @param bool $cut
     * @return string
     */
    public static function str2class(string $string, bool $cut = false): string
    {
        if ($cut) {
            $string = substr($string, 0, -1);
        }
        return \Illuminate\Support\Str::studly($string);
    }

    /**
     * Преобразования названия метода в строку
     * @param string $string
     * @return string
     */
    public static function method2str(string $string): string
    {
        $string = class_basename($string);
        return \Illuminate\Support\Str::snake($string);
    }

    /**
     * Парсинг строки и получение данных из модели
     * @param string $string
     * @return array
     */
    public static function str2data(string $string): array
    {
        // data parsing
        $array = explode(":", $string);
        $result = [];

        // Model:column_key:column_value:where:value:?order?
        if (count($array) == 5 or count($array) == 6) {
            $result = app($array[0])->where($array[3], $array[4])->orderBy($array[5] ?? 'id')->pluck($array[2], $array[1])->toArray();
        }

        // Model:column_key:column_value:?order?
        if (count($array) == 3 or count($array) == 4) {
            $result = app($array[0])->orderBy($array[3] ?? 'id')->pluck($array[2], $array[1])->toArray();
        }

        return $result;
    }

    /**
     * Поиск моделей для работы в админке
     * @return array
     */
    public static function getModels(): array
    {
        $appModels = [];

        foreach (scandir(base_path('app/Models')) as $file) {

            $pathInfo = pathinfo($file);

            if (isset($pathInfo['extension']) and $pathInfo['extension'] == 'php') {

                if ($pathInfo['filename'] != 'User' and class_exists('App\\Models\\' . $pathInfo['filename'])) {

                    $name = 'App\\Models\\' . $pathInfo['filename'];

                    if (property_exists($name, 'fastleo') == false) {
                        continue;
                    }

                    $appModels[$pathInfo['filename']] = new $name;
                }
            }
        }

        return $appModels;
    }

    /**
     * Прсмотр полей для редактирования в модели по ее названию
     * @param string $model
     * @return object
     */
    public static function getModelColumns($model): object
    {
        $model = new $model();
        $columns = collect(\Illuminate\Support\Facades\Schema::getColumnListing($model->getTable()))->flip();
        $columns = $columns->except(collect(config('fastleo.exclude.row_name')));
        foreach ($columns as $k => $v) {
            if (\Illuminate\Support\Str::endsWith($k, '_id')) {
                $columns->forget($k);
            } else {
                $columns[$k] = $model->fastleo_columns[$k];
            }
        }
        return $columns;
    }

    /**
     * Основное меню
     * @param array $models
     * @param array $menu
     * @return array
     */
    public static function getMenu(array $models, array $menu = []): array
    {
        foreach ($models as $model => $data) {
            if ($data->fastleo) {
                if ($data->fastleo_group) {
                    $menu[$data->fastleo_group][$data->fastleo] = $model;
                } else {
                    $menu[$data->fastleo] = $model;
                }
            }
        }
        ksort($menu);
        return $menu;
    }
}
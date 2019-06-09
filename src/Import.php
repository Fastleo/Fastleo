<?php

namespace Fastleo\Fastleo;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Import implements ToModel, WithHeadingRow
{
    protected $app;

    protected $columns;

    public function __construct($app, $columns = [])
    {
        $this->app = $app;
        $this->columns = $columns;
    }

    public function model(array $row)
    {
        $id = (int)$row['id'];
        unset($row['id']);

        if ($id > 0) {
            $this->app->whereId($id)->update($row);
        } else {
            $this->app->insert($row);
        }
    }
}
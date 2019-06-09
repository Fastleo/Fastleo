<?php

namespace Fastleo\Fastleo;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Export implements FromCollection, WithHeadings
{
    protected $app;

    protected $columns;

    public function __construct($app, $columns = [])
    {
        $this->app = $app;
        $this->columns = $columns;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function collection()
    {
        return $this->app->all();
    }
}
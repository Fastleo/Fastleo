<?php

namespace Fastleo\Fastleo;

/*
Fastleo::create('App\Model', function (Fastleo $fastleo) {
    $fastleo->name('column_one')->nullable();
    $fastleo->name('column_two')->nullable();
});
*/

class Columns
{
    /**
     * @var
     */
    public $columns;

    /**
     * @var string
     */
    public $model;

    /**
     * @var
     */
    private $name;

    /**
     * Fastleo constructor.
     * @param string $model
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $model
     * @param Closure $columns
     */
    public static function create(string $model, Closure $columns)
    {
        $user = new self($model);
        $columns($user);

        dd($user->getColumn());
    }

    /**
     * @param string $type
     * @param $name
     */
    public function addColumn(string $type, $name)
    {
        $this->columns[$this->model][$this->name][$type] = $name;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->columns;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        $this->addColumn('name', $name);
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->addColumn('title', $title);
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function type(string $type = 'string')
    {
        $this->addColumn('type', $type);
        return $this;
    }

    /**
     * @param bool $visible
     * @return $this
     */
    public function visible(bool $visible = false)
    {
        $this->addColumn('visible', $visible);
        return $this;
    }

    /**
     * @param bool $visible
     * @return $this
     */
    public function media(bool $visible = false)
    {
        $this->addColumn('media', $visible);
        return $this;
    }

    /**
     * @param bool $visible
     * @return $this
     */
    public function tinymce(bool $visible = false)
    {
        $this->addColumn('tinymce', $visible);
        return $this;
    }

    /**
     * @param string $placeholder
     * @return $this
     */
    public function placeholder(string $placeholder = '')
    {
        $this->addColumn('placeholder', $placeholder);
        return $this;
    }

    /**
     * @param $default
     * @return $this
     */
    public function default($default)
    {
        $this->addColumn('default', $default);
        return $this;
    }

    /**
     * @return $this
     */
    public function nullable()
    {
        $this->addColumn('nullable', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function disabled()
    {
        $this->addColumn('disabled', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function required()
    {
        $this->addColumn('required', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function readonly()
    {
        $this->addColumn('readonly', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function data($data)
    {
        $this->addColumn('data', $data);
        return $this;
    }

    /**
     * @return $this
     */
    public function reflection($reflection)
    {
        $this->addColumn('reflection', $reflection);
        return $this;
    }
}
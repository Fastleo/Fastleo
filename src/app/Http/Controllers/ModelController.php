<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ModelController extends Controller
{
    /**
     * Текущее приложение
     * @var \Illuminate\Contracts\Foundation\Application|mixed|string
     */
    public $app;

    /**
     * Пространство имен класса
     * @var string
     */
    public $namespace;

    /**
     * Основное название класса
     * @var string|null
     */
    public $name;

    /**
     * Название
     * @var string|null
     */
    public $title;

    /**
     * Название таблицы
     * @var mixed
     */
    public $table;

    /**
     * Схема таблицы
     * @var mixed
     */
    public $schema;

    /**
     * Список столбцов
     * @var
     */
    public $columns;

    /**
     * ModelController constructor.
     */
    public function __construct()
    {
        // Имя модели для работы
        $this->name = request()->segment(3);

        // namespace модели
        $this->namespace = 'App\\' . $this->name;

        // Выбираем модель для работы
        $this->app = app($this->namespace);

        // Имя таблицы
        $this->table = $this->getTable();

        // Список столбцов
        $this->schema = $this->getColumns();

        // Fastleo variables
        $this->title = $this->app->fastleo ?? $this->name;

        // Table columns
        foreach ($this->schema as $column) {
            $this->columns[$column] = $this->app->fastleo_columns[$column] ?? ['type' => $this->getColumnType($column)];
            if (!isset($this->columns[$column]['type'])) {
                $this->columns[$column]['type'] = 'string';
            }
        }
    }

    /**
     * Название основной таблицы
     * @return mixed
     */
    private function getTable()
    {
        return $this->app->getTable();
    }

    /**
     * Список столбцов таблицы
     * @return mixed
     */
    private function getColumns()
    {
        return Schema::getColumnListing($this->table);
    }

    /**
     * Типы столбцов таблицы
     * @param $column
     * @return mixed
     */
    private function getColumnType($column)
    {
        return Schema::getColumnType($this->table, $column);
    }

    /**
     * Rows list
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Список записей в таблице
        $rows = $this->app::whereNotNull('id');

        // Поиск по записям
        if ($request->has('_search')) {
            $rows->where(function ($query) use ($request) {
                foreach ($this->columns as $column => $data) {
                    if (in_array($data['type'], ['string', 'text'])) {
                        $query->orWhere($column, 'LIKE', '%' . $request->get('_search') . '%');
                    }
                }
                return $query;
            });
        }

        // Сортировка
        $rows->orderBy(isset($this->columns['sort']) ? 'sort' : 'id');

        return view('fastleo::model', [
            'rows' => $rows->paginate(15),
            'name' => $this->name,
            'title' => $this->title,
            'columns' => $this->columns,
        ]);
    }

    /**
     * Row add
     * @param Request $request
     * @param $model
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request, $model)
    {
        if ($request->isMethod('post')) {

            // дата создания
            if (isset($this->columns['created_at'])) {
                $request->request->add(['created_at' => \Carbon\Carbon::now()]);
            }

            // сортировка
            if (isset($this->columns['sort'])) {
                $request->request->add(['sort' => $this->app->max('sort') + 1]);
            }

            // возможно есть массивы
            foreach ($request->except(config('fastleo.exclude.get_list')) as $k => $value) {
                if (is_array($value)) {
                    if (isset($this->columns[$k]['type']) and $this->columns[$k]['type'] == 'include' and isset($this->columns[$k]['model'])) {
                        $relations[$this->columns[$k]['model']] = $value;
                        $request->request->add([$k => null]);
                    } else {
                        $request->request->add([$k => implode(",", $value)]);
                    }
                }
            }

            // Добавляем запись в БД
            $insert_id = $this->app->insertGetId(
                $request->except(config('fastleo.exclude.get_list'))
            );

            // Добавляем записи в зависимые таблицы
            if (isset($relations)) {
                foreach ($relations as $name => $value) {
                    $manyApp = app($name);
                    foreach ($value as $val) {
                        $many = new $manyApp;
                        $many->{Helper::method2str($this->namespace) . '_id'} = $insert_id;
                        foreach ($val as $c => $v) {
                            $many->{$c} = $v;
                        }
                        $many->save();
                    }
                }
            }

            if ($request->has('_return')) {
                header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
                die;
            } else {
                header('Location: /fastleo/app/' . $model . '/edit/' . $insert_id . '?' . $request->getQueryString());
                die;
            }
        }

        // Исключаем нередактируемые поля
        $this->columns = \Arr::except($this->columns, config('fastleo.exclude.row_name'));

        // view
        return view('fastleo::model-edit', [
            'name' => $this->name,
            'title' => $this->title,
            'columns' => $this->columns,
        ]);
    }

    /**
     * Row edit
     * @param Request $request
     * @param $model
     * @param $row_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $model, $row_id)
    {
        if ($request->isMethod('post')) {

            // дата создания
            if (isset($this->columns['updated_at'])) {
                $request->request->add(['updated_at' => \Carbon\Carbon::now()]);
            }

            // возможно есть массивы
            foreach ($request->except(config('fastleo.exclude.get_list')) as $k => $value) {
                if (is_array($value)) {
                    if (isset($this->columns[$k]['type']) and $this->columns[$k]['type'] == 'include' and isset($this->columns[$k]['model'])) {
                        $relations[$this->columns[$k]['model']] = $value;
                        $request->request->add([$k => null]);
                    } else {
                        $request->request->add([$k => implode(",", $value)]);
                    }
                }
            }

            // Обновление записи в БД
            $this->app->where('id', $row_id)->update(
                $request->except(config('fastleo.exclude.get_list'))
            );

            // Добавляем записи в зависимые таблицы
            if (isset($relations)) {
                foreach ($relations as $name => $value) {
                    $manyApp = app($name);
                    $manyApp::where(Helper::method2str($this->namespace) . '_id', $row_id)->delete();
                    foreach ($value as $val) {
                        $many = new $manyApp;
                        $many->{Helper::method2str($this->namespace) . '_id'} = $row_id;
                        foreach ($val as $c => $v) {
                            $many->{$c} = $v;
                        }
                        $many->save();
                    }
                }
            }

            if ($request->has('_return')) {
                header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
                die;
            } else {
                header('Location: /fastleo/app/' . $model . '/edit/' . $row_id . '?' . $request->getQueryString());
                die;
            }
        }

        // Запись в ДБ
        $row = $this->app::where('id', $row_id)->first();

        // Исключаем нередактируемые поля
        $this->columns = \Arr::except($this->columns, config('fastleo.exclude.row_name'));

        return view('fastleo::model-edit', [
            'row' => $row,
            'name' => $this->name,
            'title' => $this->title,
            'columns' => $this->columns,
        ]);
    }

    /**
     * Delete row
     * @param Request $request
     * @param $model
     * @param $row_id
     */
    public function delete(Request $request, $model, $row_id)
    {
        if (isset($this->columns['sort'])) {
            $row = $this->app->where('id', $row_id)->first();
            $this->app->where('sort', '>', $row->sort)->decrement('sort');
        }
        $this->app->where('id', $row_id)->delete();
        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Включение и отключение меню
     * @param Request $request
     * @param $model
     * @param $row_id
     */
    public function menu(Request $request, $model, $row_id)
    {
        $menu = 1;
        $row = $this->app::where('id', $row_id)->first();
        if ($row->menu == 1) {
            $menu = 0;
        }
        $this->app::where('id', $row_id)->update([
            'menu' => $menu
        ]);

        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Sorting up
     * @param Request $request
     * @param $model
     * @param $row_id
     */
    public function up(Request $request, $model, $row_id)
    {
        $current = $this->app::whereId($row_id);
        $current_sort = $current->first();

        if (is_null($current_sort->sort)) {
            $this->sortingFix($request, $model);
        }

        $prev = $this->app::where('sort', '<', $current_sort->sort)->orderBy('sort', 'desc')->first();

        if (!is_null($prev)) {
            $current->update([
                'sort' => $prev->sort
            ]);
            $this->app::whereId($prev->id)->update([
                'sort' => $current_sort->sort
            ]);
        }

        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Sorting down
     * @param Request $request
     * @param $model
     * @param $row_id
     */
    public function down(Request $request, $model, $row_id)
    {
        $current = $this->app::whereId($row_id);
        $current_sort = $current->first();

        if (is_null($current_sort->sort)) {
            $this->sortingFix($request, $model);
        }

        $next = $this->app::where('sort', '>', $current_sort->sort)->orderBy('sort', 'asc')->first();

        if (!is_null($next)) {
            $current->update([
                'sort' => $next->sort
            ]);
            $this->app::whereId($next->id)->update([
                'sort' => $current_sort->sort
            ]);
        }

        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Исправление сортировки
     * @param Request $request
     * @param $model
     */
    public function sortingFix(Request $request, $model)
    {
        if (isset($this->columns['sort'])) {
            $rows = $this->app::orderBy('sort')->orderBy('id')->get();
            $sort = 1;
            foreach ($rows as $row) {
                $this->app::where('id', $row->id)->update([
                    'sort' => $sort
                ]);
                $sort++;
            }
        }
        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Выключить все записи
     * @param Request $request
     * @param $model
     */
    public function menuOn(Request $request, $model)
    {
        if (isset($this->columns['menu'])) {
            $this->app::query()->update([
                'menu' => 1
            ]);
        }
        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Включить все записи
     * @param Request $request
     * @param $model
     */
    public function menuOff(Request $request, $model)
    {
        if (isset($this->columns['menu'])) {
            $this->app::query()->update([
                'menu' => 0
            ]);
        }
        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }

    /**
     * Export rows
     * @param Request $request
     * @return \Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function rowsExport(Request $request)
    {
        $export = new Export($this->app, $this->schema);
        return \Maatwebsite\Excel\Facades\Excel::download($export, $this->table . '_' . date("YmdHis") . '.xlsx');
    }

    /**
     * Import rows
     * @param Request $request
     * @param $model
     */
    public function rowsImport(Request $request, $model)
    {
        $import = new Import($this->app, $this->schema);
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('import'));

        header('Location: ' . route('fastleo.model', [$model]) . '?' . $request->getQueryString());
        die;
    }
}
<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Maatwebsite\Excel\Facades\Excel;

class ModelController extends Controller
{
    public $app, $columns, $model, $name, $schema, $table;

    public $fastleo_model, $fastleo_columns;

    public $exclude_get_list = ['id', '_token', 'page', 'search'];

    public $exclude_list_type = ['text', 'longtext'];
    public $exclude_list_name = ['sort', 'menu', 'password', 'remember_token', 'admin'];

    public $exclude_row_type = [];
    public $exclude_row_name = ['id', 'sort', 'menu', 'password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * ModelController constructor.
     */
    public function __construct()
    {
        // Model name
        $this->name = request()->segment(3);

        // Model namespace
        $this->model = 'App\\' . app()->appmodels[$this->name]['name'];

        // Model exist
        if (!class_exists($this->model)) {
            return false;
        }

        // Start App
        $this->app = $this->getModel();

        // Table name
        $this->table = $this->getTable();

        // Table column list
        $this->schema = $this->getColumns();

        // Exclude visible columns
        $this->exclude_list_name = array_merge($this->exclude_list_name, $this->app->getHidden());
        $this->exclude_row_name = array_merge($this->exclude_row_name, $this->app->getHidden());

        // Fastleo variables
        $this->fastleo_model = $this->app->fastleo_model ?: [];
        $this->fastleo_columns = $this->app->fastleo_columns ?: [];

        // Table columns
        if (count($this->schema) > 0) {
            foreach ($this->schema as $column) {
                $this->columns[$column] = $this->getColumnType($column);
            }
        } else {
            die('Not exist table ' . $this->table);
        }
    }

    /**
     * @return mixed
     */
    private function getModel()
    {
        return app($this->model);
    }

    /**
     * @return mixed
     */
    private function getTable()
    {
        return $this->app->getTable();
    }

    /**
     * @return mixed
     */
    private function getColumns()
    {
        return Schema::getColumnListing($this->table);
    }

    /**
     * @param $column
     * @return mixed
     */
    private function getColumnType($column)
    {
        return Schema::getColumnType($this->table, $column);
    }

    /**
     * Unset empty value in array
     * @param array $array
     * @param array $exclude
     * @param array $inclusion
     * @return array
     */
    private function unsetForeach(array $array, array $exclude = [], array $inclusion = [])
    {
        foreach ($array as $k => $r) {
            if ($r == '') {
                unset($array[$k]);
            }
            if (count($exclude) > 0 and in_array($k, $exclude)) {
                unset($array[$k]);
            }
            if (count($inclusion) > 0 and !in_array($k, $inclusion)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    /**
     * Null value in array
     * @param array $array
     * @param array $inclusion
     * @return array
     */
    private function nullForeach(array $array, array $inclusion = [])
    {
        foreach ($array as $k => $r) {
            if ($r == '') {
                $array[$k] = NULL;
            }
            if (count($inclusion) > 0 and !in_array($k, $inclusion)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    /**
     * @return array
     */
    private function parsColumns()
    {
        foreach ($this->fastleo_columns as $k => $v) {
            // value = key
            $this->fastleo_columns[$k]['key'] = true;

            if (isset($v['data']) and is_string($v['data'])) {
                // data parsing
                $prs = explode(":", $v['data']);
                // create array
                if (count($prs) == 5) {
                    // Model:column_key:column_value:where:value
                    $this->fastleo_columns[$k]['data'] = app($prs[0])->where($prs[3], $prs[4])->orderBy('id')->pluck($prs[2], $prs[1])->toArray();
                } elseif (count($prs) == 4) {
                    // Model:column_value:where:value
                    $this->fastleo_columns[$k]['data'] = app($prs[0])->where($prs[2], $prs[3])->orderBy('id')->pluck($prs[1])->toArray();
                    // text to array
                    if (isset($this->fastleo_columns[$k]['delimiter'])) {
                        foreach ($this->fastleo_columns[$k]['data'] as $value) {
                            $columns[$k]['data'] = explode($this->fastleo_columns[$k]['delimiter'], $value);
                        }
                        if (isset($columns[$k]['data'])) {
                            $this->fastleo_columns[$k]['data'] = $columns[$k]['data'];
                        }
                    }
                    // value = value
                    $this->fastleo_columns[$k]['key'] = null;
                } elseif (count($prs) == 3) {
                    // Model:column_key:column_value
                    $this->fastleo_columns[$k]['data'] = app($prs[0])->whereNotNull('id')->orderBy('id')->pluck($prs[2], $prs[1])->toArray();
                } else {
                    // error
                    $this->fastleo_columns[$k]['data'] = [];
                }
            }

        }

        return $this->fastleo_columns;
    }

    /**
     * @param $model_name
     * @return string
     */
    private function getModelName($model_name)
    {
        $array_name = explode('_', $model_name);
        foreach ($array_name as $k => $name) {
            $array_name[$k] = ucfirst($name);
        }
        return implode('', $array_name);
    }

    /**
     * Query search
     * @param $query
     * @param $search
     * @return mixed
     */
    private function search($search)
    {
        $query = $this->app::whereNull('id');
        foreach ($this->columns as $column => $type) {
            if (in_array($type, ['string', 'text'])) {
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        }
        return $query;
    }

    /**
     * Rows list
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Search all rows
        if ($request->get('search')) {
            $query = $this->search($request->get('search'));
        } else {
            $query = $this->app::whereNotNull('id');
        }

        // sorting
        if (isset($this->columns['sort'])) {
            $query->orderBy('sort')->orderBy('id');
        } else {
            $query->orderBy('id');
        }

        // all rows
        $rows = $query->paginate(15);

        return view('fastleo::model', [
            'exclude_type' => $this->exclude_list_type,
            'exclude_name' => $this->exclude_list_name,
            'model_columns' => $this->columns,
            'model_title' => ucfirst($this->name),
            'model_name' => $this->name,
            'model' => $this->fastleo_model,
            'rows' => $rows,
            'f' => $this->fastleo_columns,
        ]);
    }

    /**
     * Row add
     * @param Request $request
     * @param $model
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request, $model)
    {
        // add
        if ($request->except($this->exclude_get_list)) {

            // дата создания
            if (isset($this->columns['created_at'])) {
                $request->request->add(['created_at' => \Carbon\Carbon::now()]);
            }

            // сортировка
            if (isset($this->columns['sort'])) {
                $request->request->add(['sort' => $this->app->max('sort') + 1]);
            }

            // возможно есть массивы
            foreach ($request->except($this->exclude_get_list) as $key => $value) {
                if (is_array($value)) {
                    if (!isset($this->fastleo_columns[$key]['type']) or $this->fastleo_columns[$key]['type'] != 'include') {
                        $request->request->add([$key => implode(",", $value)]);
                    } else {
                        $many[$key] = $value;
                        $request->request->add([$key => null]);
                    }
                }
            }

            // add row
            $insert_id = $this->app->insertGetId($request->except($this->exclude_get_list));

            // include
            if (isset($many)) {
                foreach ($many as $key => $value) {
                    if (count($value) > 0) {
                        $manyName = substr($key, 0, -1);
                        $manyApp = app('App\\' . self::getModelName($manyName));
                        foreach ($value as $v) {
                            if (!is_null($v)) {
                                $manyApp::insert([
                                    $model . '_id' => $insert_id,
                                    $manyName => $v,
                                ]);
                            }
                        }
                    }
                }
            }

            if (!is_null($request->get('id'))) {
                header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
            } else {
                header('Location: /fastleo/app/' . $model . '/edit/' . $insert_id . '?' . $request->getQueryString());
            }
            die;
        }

        // view
        return view('fastleo::model-edit', [
            'exclude_type' => $this->exclude_row_type,
            'exclude_name' => $this->exclude_row_name,
            'model_columns' => $this->columns,
            'model_title' => ucfirst($this->name),
            'model_name' => $this->name,
            'model' => $this->fastleo_model,
            'f' => $this->parsColumns(),
        ]);
    }

    /**
     * Row edit
     * @param Request $request
     * @param $model
     * @param $row_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $model, $row_id)
    {
        // edit
        if ($request->except($this->exclude_get_list)) {
            if (isset($this->columns['updated_at'])) {
                $request->request->add(['updated_at' => \Carbon\Carbon::now()]);
            }

            // возможно есть массивы
            foreach ($request->except($this->exclude_get_list) as $key => $value) {
                if (is_array($value)) {
                    if (!isset($this->fastleo_columns[$key]['type']) or $this->fastleo_columns[$key]['type'] != 'include') {
                        $request->request->add([$key => implode(",", $value)]);
                    } else {
                        $many[$key] = $value;
                        $request->request->add([$key => null]);
                    }
                }
            }

            // include
            if (isset($many)) {
                foreach ($many as $key => $value) {
                    if (count($value) > 0) {
                        $manyName = substr($key, 0, -1);
                        $manyApp = app('App\\' . self::getModelName($manyName));
                        $manyApp::where($model . '_id', $row_id)->delete();
                        foreach ($value as $v) {
                            if (!is_null($v)) {
                                $manyApp::insert([
                                    $model . '_id' => $row_id,
                                    $manyName => $v,
                                ]);
                            }
                        }
                    }
                }
            }

            $this->app->where('id', $row_id)->update($request->except($this->exclude_get_list));

            if (!is_null($request->get('id'))) {
                header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
            } else {
                header('Location: /fastleo/app/' . $model . '/edit/' . $row_id . '?' . $request->getQueryString());
            }
            die;
        }

        // view
        $row = $this->app::where('id', $row_id)->first();
        return view('fastleo::model-edit', [
            'exclude_type' => $this->exclude_row_type,
            'exclude_name' => $this->exclude_row_name,
            'model_columns' => $this->columns,
            'model_title' => ucfirst($this->name),
            'model_name' => $this->name,
            'model' => $this->fastleo_model,
            'row_id' => $row_id,
            'row' => $row,
            'f' => $this->parsColumns(),
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
        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
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

        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
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
        $current = $this->app::where('id', $row_id);
        $current_sort = $current->first();

        $prev = $this->app::where('sort', '<', $current_sort->sort)->orderBy('sort', 'desc');
        $prev_sort = $prev->first();

        if (!is_null($prev_sort)) {
            $current->update([
                'sort' => $prev_sort->sort
            ]);
            $this->app::where('id', $prev_sort->id)->update([
                'sort' => $current_sort->sort
            ]);
        }

        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
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
        $current = $this->app::where('id', $row_id);
        $current_sort = $current->first();

        $next = $this->app::where('sort', '>', $current_sort->sort)->orderBy('sort', 'asc');
        $next_sort = $next->first();

        if (!is_null($next_sort)) {
            $current->update([
                'sort' => $next_sort->sort
            ]);
            $this->app::where('id', $next_sort->id)->update([
                'sort' => $current_sort->sort
            ]);
        }

        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
        die;
    }

    /**
     * Добавление сортировки
     * @param Request $request
     * @param $model
     */
    public function sortingAdd(Request $request, $model)
    {
        if (!isset($this->columns['sort'])) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->integer('sort')->after('id')->nullable();
            });
        }
        $this->columns['sort'] = 'integer';
        $this->sortingFix($request, $model);
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
        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
        die;
    }

    /**
     * ДОбавление меню, если его нет
     * @param Request $request
     * @param $model
     */
    public function menuAdd(Request $request, $model)
    {
        if (!isset($this->columns['menu'])) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->integer('menu')->after('id')->default('1');
            });
        }
        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
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
        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
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
        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
        die;
    }

    /**
     * Export rows
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    public function rowsExport(Request $request)
    {
        $export = new Export($this->app, $this->schema);
        return Excel::download($export, $this->table . '_' . date("YmdHis") . '.xlsx');
    }

    /**
     * Import rows
     * @param Request $request
     * @param $model
     * @throws \League\Csv\Exception
     */
    public function rowsImport(Request $request, $model)
    {
        $import = new Import($this->app, $this->schema);
        Excel::import($import, $request->file('import'));

        header('Location: /fastleo/app/' . $model . '?' . $request->getQueryString());
        die;
    }
}
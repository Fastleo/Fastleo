<?php

namespace Fastleo\Fastleo\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class InfoController extends Controller
{
    /**
     * Info page
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $params = [];
        $phpversion = explode("-", phpversion());

        if (env('DB_CONNECTION') == 'mysql') {
            $typeDB = DB::select('SELECT version() as server_version;');
        } elseif (env('DB_CONNECTION') == 'pgsql') {
            $typeDB = DB::select('SHOW server_version;');
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            $typeDB = DB::select('SELECT sqlite_version() AS server_version;');
        } else {
            $typeDB[0] = new \stdClass();
            $typeDB[0]->server_version = '';
        }

        $params[] = [
            'title' => 'Версия Fastleo',
            'value' => config('fastleo_composer')->version
        ];

        $params[] = [
            'title' => 'Версия Laravel',
            'value' => app()->version()
        ];

        $params[] = [
            'title' => 'Версия PHP',
            'value' => $phpversion[0]
        ];

        $params[] = [
            'title' => 'Драйвер БД',
            'value' => env('DB_CONNECTION') . ' (' . ($typeDB[0]->server_version ?? '') . ')'
        ];

        $params[] = [
            'title' => 'Максиму для загрузки',
            'value' => ini_get("upload_max_filesize")
        ];

        return view('fastleo::info', [
            'params' => $params
        ]);
    }

    /**
     * Clear cache
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function clear()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        return redirect(route('fastleo.info'));
    }
}
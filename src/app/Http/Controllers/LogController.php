<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public $log = 'storage/logs/laravel.log';

    /**
     * View lo rows
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (file_exists($this->log)) {
            $log_file = file_get_contents(base_path($this->log));
        } else {
            $log_file = file_put_contents(base_path($this->log), '');
        }
        $logs = explode("\n", $log_file);
        foreach ($logs as $k => $l) {
            if (substr($l, 0, 3) != '[20') {
                unset($logs[$k]);
            }
        }
        return view('fastleo::log', [
            'logs' => array_reverse($logs)
        ]);
    }

    /**
     * Clear lo file
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function clear()
    {
        file_put_contents(base_path($this->log), '');
        header('Location: /fastleo/log');
        die;
    }
}
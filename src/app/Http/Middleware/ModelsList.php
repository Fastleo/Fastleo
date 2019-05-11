<?php

namespace Fastleo\Fastleo;

use Closure;
use Illuminate\Http\Request;

class ModelsList
{
    /**
     * Models list
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('fastleo/*')) {
            $appmodels = [];
            foreach (scandir(base_path('app')) as $file) {
                $pathInfo = pathinfo($file);
                if (isset($pathInfo['extension']) and $pathInfo['extension'] == 'php') {
                    if (class_exists('App\\' . $pathInfo['filename'])) {
                        $name = 'App\\' . $pathInfo['filename'];
                        $app = new $name();
                        if (isset($app->fastleo) and $app->fastleo == false) {
                            continue;
                        }
                        $appmodels[strtolower($pathInfo['filename'])] = [
                            'icon' => $app->fastleo_model['icon'] ?? null,
                            'name' => $app->fastleo_model['name'] ?? $pathInfo['filename'],
                            'title' => $app->fastleo_model['title'] ?? $pathInfo['filename'],
                        ];
                    }
                }
            }
            $request->appmodels = $appmodels;
        }
        return $next($request);
    }
}
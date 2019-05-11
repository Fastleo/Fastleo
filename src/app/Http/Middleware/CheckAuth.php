<?php

namespace Fastleo\Fastleo;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
    /**
     * Check admin auth
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('fastleo/*') or $request->is('fastleo')) {
            if ($request->session()->get('admin') == 1 and $request->path() == 'fastleo') {
                return redirect('/fastleo/info');
            }
            if (is_null($request->session()->get('admin')) and $request->path() != 'fastleo') {
                return redirect('/fastleo');
            }
        }
        return $next($request);
    }
}
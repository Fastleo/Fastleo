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
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->segment(1) == 'fastleo') {
            if ($request->path() != 'fastleo') {
                if (is_null($request->session()->get('fastleo_admin')) or $request->session()->get('fastleo_admin') == 0) {
                    return redirect('/fastleo');
                }
            }
            if ($request->session()->get('fastleo_admin') == 1 and $request->path() == 'fastleo') {
                return redirect('/fastleo/info');
            }
        }
        return $next($request);
    }
}
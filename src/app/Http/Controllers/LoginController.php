<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        // Проверка существования конфига
        if (is_null(config('fastleo.exclude'))) {
            echo 'run console command: php artisan vendor:publish --tag=fastleo --force';
        }

        if ($request->session()->has('fastleo')) {
            return redirect(route('fastleo.info'));
        }

        if ($request->post('email') and $request->post('password')) {
            $user = User::where('email', $request->post('email'))->where('fastleo_admin', 1)->first();
            if (!is_null($user)) {
                if (Hash::check($request->post('password'), $user->getAuthPassword()) and $user->fastleo_admin == 1) {
                    $request->session()->put('fastleo', $user->fastleo_admin);
                    $request->session()->save();
                    return redirect(route('fastleo.info'));
                }
            }
        }
        return view('fastleo::login');
    }

    /**
     * Logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $request->session()->put('fastleo', false);
        $request->session()->flush();
        $request->session()->save();
        return redirect(route('fastleo.login'));
    }
}
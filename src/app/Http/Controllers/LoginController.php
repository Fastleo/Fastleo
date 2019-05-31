<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class LoginController extends Controller
{
    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        // Аутентификация
        if (Auth::attempt([
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'fastleo_admin' => true,
        ], true)) {
            // успешна
            Auth::login(Auth::user(), true);
            return redirect(route('fastleo.info'));
        }

        if(Auth::id()) {
            return redirect(route('fastleo.info'));
        }

        return view('fastleo::login');
    }

    /**
     * Logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect(route('fastleo.login'));
    }
}
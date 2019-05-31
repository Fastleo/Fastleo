<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $users = User::paginate(15);
        return view('fastleo::users', [
            'users' => $users
        ]);
    }

    public function add(Request $request)
    {
        if ($request->input()) {
            $user = new User;
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->fastleo_admin = $request->get('fastleo_admin');
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return redirect(route('fastleo.users.edit', ['id' => $user->id]) . '?' . $request->getQueryString());
        }
        return view('fastleo::users-edit');
    }

    public function edit(Request $request, $user_id)
    {
        $user = User::whereId($user_id)->first();
        if ($request->input()) {
            $user->name = $request->get('name') ?? $user->name;
            $user->email = $request->get('email') ?? $user->email;
            $user->fastleo_admin = $request->input('fastleo_admin') ?? $user->fastleo_admin;
            if ($request->get('password') != '') {
                $user->password = Hash::make($request->get('password'));
            }
            $user->save();
        }
        return view('fastleo::users-edit', [
            'user' => $user
        ]);
    }

    public function delete(Request $request, $user_id)
    {
        User::whereId($user_id)->delete();
        return redirect(route('fastleo.users') . '?' . $request->getQueryString());
    }
}
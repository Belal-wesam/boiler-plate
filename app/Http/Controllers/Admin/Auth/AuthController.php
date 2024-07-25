<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        #todos 
        #implement login using the username or email
        #use the custom guard called admins to authenticate routes 
        #work on roles , permissions and seeders for super admin

        #retrive credentials
        $credntials['password'] = $request->password;

        if (filter_var($request->login_field, FILTER_VALIDATE_EMAIL)) {
            $credntials['email'] = $request->login_field;
        } else {
            $credntials['username'] = $request->login_field;
        }

        if (Auth::guard('admin')->attempt($credntials)) {
            return to_route('admin.index');
        }

        return back()->with('processFail', 'failed');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return to_route('admin.login');
    }
}

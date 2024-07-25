<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $intended_route = $request->route()->getName();
        if ((strpos($intended_route, 'admin')) == false) {
            $intended_route = route('admin.login');
        } else {
            $intended_route = route('login');
        }
        return $request->expectsJson() ? null : $intended_route;
    }
}

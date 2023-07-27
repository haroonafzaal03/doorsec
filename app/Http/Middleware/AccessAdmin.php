<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Role;
use App\User;


class AccessAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $slug = '')
    {
        $roles = Auth::user()->roles;
        foreach ($roles as $role) {

            if (Auth::user()->return_role_detil($role->name)->pluck('slug')->contains($slug)) {
                return $next($request);
            }
        }
        return redirect('/');
    }
}

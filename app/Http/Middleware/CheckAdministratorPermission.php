<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckAdministratorPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('user_token', $request->header('UT'))->first();
        if(!$request->header('UT') || $user->role_id == 3) {
            abort(403);
        };
        return $next($request);
    }
}

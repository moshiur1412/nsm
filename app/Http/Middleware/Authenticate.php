<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Auth\AuthenticationException as exception;

class Authenticate extends Middleware
{

	protected $guards = [];

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {

    	if (! $request->expectsJson()) {

    		if ($request->path() == 'app/*') {
    			return route('app.login');
    		}
    		if ($request->path() == 'back/*') {
    			return route('back.login');
    		}

    		// return abort(404);
    	}

    }

}

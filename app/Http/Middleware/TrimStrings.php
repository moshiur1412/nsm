<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Closure;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
    	'login_password',
    	'password_confirmation',
    ];

    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next) {

        $input = $request->all();

        $trimmed = [];

        foreach($input as $key => $val)
        {
            if (!is_string($val)) continue;
            $trimmed[$key] = preg_replace('/(^\s+)|(\s+$)/u', '', $val);
            $trimmed[$key] = mb_convert_kana($trimmed[$key], 's');
            $trimmed[$key] = mb_convert_kana($trimmed[$key], 'n');
        }

        $request->merge($trimmed);
        return $next($request);
    }
}

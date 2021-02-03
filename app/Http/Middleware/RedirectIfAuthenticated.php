<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    	// After login with page redirect by middleware !';
    	switch ($guard) {
    		case 'admin':
    		if (Auth::guard($guard)->check()) {
    			return redirect('/back/admins');
    		}
    		break;

    		case 'judge':
    		if (Auth::guard($guard)->check()) {
    			return redirect('/back/judgeSubsidyApplication');
    		}
    		break;

            case 'export':
            if (Auth::guard($guard)->check()) {
                return redirect('/export/applications');
            }
            break;

    		default:
    		if (Auth::guard($guard)->check()) {
    			return redirect('app/mypage');
    		}
    		break;
    	}
    	return $next($request);
    }

}

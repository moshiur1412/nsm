<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
    	'password',
    	'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
    	parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($this->isHttpException($exception)) {

            // 401
            if($exception->getStatusCode() == 401) {
                return response()->view('errors.401');
            }
            // 403
            if($exception->getStatusCode() == 403) {
                return response()->view('errors.403');
            }
            // 405
            if($exception->getStatusCode() == 405) {
                return response()->view('errors.405');
            }
            // 404
            if($exception->getStatusCode() == 404) {
                return response()->view('errors.404');
            }
            // 419
            if($exception->getStatusCode() == 419) {
                return response()->view('errors.419');
            }
            // 500
            if($exception->getStatusCode() == 500) {
                return response()->view('errors.500');
            }
        }
      if ($exception instanceof  \Illuminate\Http\Exceptions\PostTooLargeException) {
    		return response()->view('errors.posttoolarge');
    	}
    	if ($exception instanceof HttpResponseException) {
    		return response()->view('errors.500');
    	}
    	elseif ($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {
    		return abort(404);return response()->view('errors.404');
    	}

    	elseif ($exception instanceof AuthorizationException) {
    		return abort(403);
    	}
    	elseif($exception instanceof HttpException){
    		return abort(401);
    	}
    	elseif($exception instanceof RequestException) {
    		return abort(404);
    	}
    	elseif($exception instanceof MethodNotAllowedHttpException)
    	{
    		return abort(404);
      }
    	elseif ($exception instanceof \BadMethodCallException) {
    		return abort(404);
      }

    	return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

    	if ($request->expectsJson()) {
    		return response()->json(['error' => 'Unauthenticated.'], 401);
    	}
    	$guard = array_get($exception->guards(), 0);
    	switch ($guard) {
    		case 'admin':
    		$login = 'back.login';
    		break;
    		case 'judge':
    		$login = 'back.login';
    		break;
    		default:
    		$login = 'app.login';
    		break;
    	}
    	return redirect()->guest(route($login));

    }

}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
    	
    	if ($exception instanceof TokenMismatchException){
    		// Redirect to a form. Here is an example of how I handle mine
    	    return redirect('/')->with('msg',"คุณไม่ได้ใช้งานหน้าจอนานเกินเวลาที่กำหนด กรุณาลองอีกครั้ง");
    	}
    	
    	$data = array(
    		"exception" => $exception,
    	    "request" => $request,
    	);
    	$errors = json_decode($exception->getMessage());
    	if(isset($errors->data)){
    	    $msg = $errors->data;
    	}else{
    	    $msg = $data['exception']->getMessage();
    	}
    	if($msg != ""){
        	Mail::send('email/error',$data,function($message) use ($data){
        	    
        	    $errors = json_decode($data['exception']->getMessage());
        	    if(isset($errors->data)){
        	        $msg = $errors->data;
        	    }else{
        	        $msg = $data['exception']->getMessage();
        	    }
        	    
        	    $message->to(['thachie@tuff.co.th','oak@tuff.co.th']);
        	    $message->from('error@fastship.co', 'FastShip Error Report');
        	    $message->subject('FastShip - มีข้อผิดพลาดเกิดขึ้น : ' . substr($msg,0,150) );
        	});
    	}
    	
    	return response()->view('errors/404', $data, 500);
        //return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}

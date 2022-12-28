<?php

namespace App\Exceptions;

use BadMethodCallException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\View\ViewException;
use InvalidArgumentException;
use Psy\Exception\FatalErrorException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if($e instanceof ModelNotFoundException){
                return response()->json([
                    'Message' => 'User Not Found'
                ],404);
            }

            if($e instanceof BadMethodCallException){
                return response()->json([
                    'Message' => 'Something Went Wrong'
                ],400);
            }

            if($e instanceof NotFoundHttpException){
                return response()->json([
                    'Message' => 'Wrong Url'
                ],404);
            }

            if($e instanceof TooManyRequestsHttpException){
                return response()->json([
                    'Message' => 'Something Went Wrong, Try Again'
                ],428);
            }

            if($e instanceof TooManyRedirectsException){
                return response()->json([
                    'Message' => 'Something Went Wrong, Try Again'
                ],428);
            }

            if($e instanceof  QueryException){
                return response()->json([
                    'Message' => 'Please Try Again'
                ],500);
            }

            if($e instanceof ServerException){
                return response()->json([
                    'Message' => 'Not Found'
                ],500);
            }

            if($e instanceof FileNotFoundException){
                return response()->json([
                    'Message' => 'Not Found'
                ],404);
            }

            if($e instanceof FatalErrorException){
                return response()->json([
                    'Message' => 'Something Went Wrong'
                ],404);
            }

            if($e instanceof InvalidArgumentException){
                return response()->json([
                    'Message' => 'Not Found'
                ],404);
            }

            if($e instanceof ViewException){
                return response()->json([
                    'Message' => 'Undefined URL'
                ],404);  
            }

            if($e instanceof RuntimeException){
                return response()->json([
                    'Message' => 'Something Went Wrong'
                ],400);
            }
        })->stop();
    }
}
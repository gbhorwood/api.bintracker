<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<string>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<string>
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
            //
        });
    }

    /**
     * Override of render() to return nicer responses when custom middelware
     * throws errors.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     */
    public function render($request, Throwable $exception)
    {
        if ($exception->getMessage() == "adminonly") {
            return response()->json([
                "error" =>  "Admin Only",
                "details" =>  null,
            ], $exception->getStatusCode()); // @phpstan-ignore-line
        }
        return parent::render($request, $exception);
    }
}

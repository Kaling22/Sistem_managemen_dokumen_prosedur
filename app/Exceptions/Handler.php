<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

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
     * Menangani error saat user tidak terautentikasi (Sesi Habis)
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        /**
         * redirect()->guest() adalah kunci utama. 
         * Ia menyimpan URL yang sedang diakses user ke dalam session 
         * sebelum melempar user ke halaman login.
         */
        return redirect()->guest(route('login'))
            ->with('loginError', 'Sesi Anda telah berakhir. Silakan login kembali.');
    }

    /**
     * Menangani error spesifik seperti Page Expired (419)
     */
    public function render($request, Throwable $exception)
    {
        // Jika terjadi error 419 (CSRF Token Mismatch) karena sesi habis di latar belakang
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('/')
                ->with('loginError', 'Halaman kedaluwarsa karena sesi habis. Silakan login kembali.');
        }

        return parent::render($request, $exception);
    }
}
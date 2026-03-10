<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return redirect()->route('admin.unauthorized')
                ->with('error', 'You do not have permission to access this page.');
        }

        return parent::render($request, $exception);
    }
}

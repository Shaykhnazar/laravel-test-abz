<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('success', function (array $data = [], $status = 200) {
            return Response::json([
                'success'  => true,
                ...$data,
            ], $status);
        });

        Response::macro('error', function ($message, $status = 400) {
            return Response::json([
                'success'  => false,
                'message' => $message,
            ], $status);
        });
    }
}

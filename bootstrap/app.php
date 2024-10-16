<?php

use App\Exceptions\CustomValidationException;
use App\Exceptions\InvalidUserIdException;
use App\Http\Middleware\EnsureTokenIsValidMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'token-is-valid' => EnsureTokenIsValidMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $exception, Request $request) {
            if (!$request->wantsJson()) {
                return null; // Laravel handles as usual
            }

            throw CustomValidationException::withMessages(
                $exception->validator->getMessageBag()->getMessages()
            );
        });
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($request->wantsJson() && $request->is('api/*')) {
                return response()->error('User not found', 404);
            }

            return response()->error('Model not found', 404);
        });
        $exceptions->render(function (InvalidUserIdException $exception, Request $request) {
            return response()->error($exception->getMessage(), $exception->getCode());
        });
    })->create();

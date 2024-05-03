<?php

  use App\Http\Middleware\HandleInertiaRequests;
  use App\Payloads\WebResponsePayload;
  use Illuminate\Foundation\Application;
  use Illuminate\Foundation\Configuration\Exceptions;
  use Illuminate\Foundation\Configuration\Middleware;
  use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
  use Illuminate\Http\Request;
  use Illuminate\Validation\ValidationException;


  return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      api: __DIR__ . '/../routes/api.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
      $middleware->validateCsrfTokens(
        except: ['api/*', 'login']
      );

      $middleware->web(append: [
        HandleInertiaRequests::class,
        AddLinkHeadersForPreloadedAssets::class,
      ]);
      $middleware->statefulApi();

    })
    ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->render(function (ValidationException $validationException, Request $request) {
        return response()
          ->json(
            (new WebResponsePayload(responseMessage: "Validation error", errorInformation: $validationException->validator->getMessageBag()))
              ->getJsonResource())->setStatusCode(400);
      });

    })
    ->create();

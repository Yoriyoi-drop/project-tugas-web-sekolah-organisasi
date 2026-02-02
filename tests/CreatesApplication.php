<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Replace the api.version middleware alias with a mock version to avoid parameter issues during testing
        $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
        $routeMiddleware = $kernel->getRouteMiddleware();

        // Replace the api.version alias with a mock middleware that handles parameters
        $app['router']->aliasMiddleware('api.version', \App\Http\Middleware\ApiVersionMiddleware::class);

        // Mock the ApiVersionMiddleware before routes are loaded to avoid parameter issues during testing
        $app->bind(\App\Http\Middleware\ApiVersionMiddleware::class, function () {
            return new class {
                public function handle($request, $next, $version = 'v2')
                {
                    return $next($request);
                }
            };
        });

        // For testing, we'll load API routes normally but with mocked middleware
        $api = $app->basePath('routes' . DIRECTORY_SEPARATOR . 'api.php');
        if (file_exists($api)) {
            $app->router->group(['prefix' => 'api', 'middleware' => 'api'], function () use ($api) {
                require $api;
            });
        }

        return $app;
    }
}

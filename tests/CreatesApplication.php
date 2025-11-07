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

        // Ensure API routes are available in the test application instance
        $api = $app->basePath('routes' . DIRECTORY_SEPARATOR . 'api.php');
        if (file_exists($api)) {
            $app->router->group(['prefix' => 'api', 'middleware' => 'api'], function () use ($api) {
                require $api;
            });
        }

        return $app;
    }
}

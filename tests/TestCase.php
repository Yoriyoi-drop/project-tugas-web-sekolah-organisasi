<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Http\Middleware\ApiVersionMiddleware;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Base TestCase for Pest / PHPUnit tests

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the ApiVersionMiddleware to avoid parameter issues during testing
        $this->app->bind(ApiVersionMiddleware::class, function () {
            return new class {
                public function handle($request, $next, $version = 'v2')
                {
                    return $next($request);
                }
            };
        });

        // Replace the 'api' middleware group to remove the api.version middleware for testing
        $this->app['router']->middlewareGroup('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Disable CSRF verification for all feature tests
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }
}

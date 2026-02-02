<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

trait DisableCsrfForTesting
{
    protected function setUpTraits(): void
    {
        parent::setUpTraits();
        
        // Disable CSRF token verification for all feature tests
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
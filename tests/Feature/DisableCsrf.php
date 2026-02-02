<?php

namespace Tests\Feature;

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

trait DisableCsrf
{
    protected function disableCsrfToken()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
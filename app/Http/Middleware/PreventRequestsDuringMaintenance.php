<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add URIs that should be accessible during maintenance
        'admin/*', // Allow admin panel access during maintenance
        'api/*',   // Allow API access during maintenance if needed
    ];
}

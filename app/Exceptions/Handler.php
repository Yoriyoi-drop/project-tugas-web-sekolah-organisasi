<?php
namespace App\Exceptions;

use Exception;

class Handler
{
    public function render($request, Exception $e)
    {
        return 'Exception: ' . $e->getMessage();
    }
}

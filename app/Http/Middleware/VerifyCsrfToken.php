<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login/google/callback', // Add the URL for the Google login callback route here
        'login/facebook/callback', // Add the URL for the Facebook login callback route here
        // Add any other routes you want to exclude from CSRF protection here
    ];
}

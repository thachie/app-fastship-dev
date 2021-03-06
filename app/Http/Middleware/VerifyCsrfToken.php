<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'customer/add-channel',
        'joinus',
        'liff/*',
        'kbank/*',
        'etsy/*',
        'pickup_detail/*',
        'checkout',
    ];
}

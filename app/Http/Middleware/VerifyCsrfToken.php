<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('cart_session_id')) {
            Log::debug('Initializing new cart session');
            $request->session()->put('cart_session_id', str_random(40));
        }
        
        return parent::handle($request, $next);
    }

    protected $except = [
        // ...existing code...
    ];
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Ensure2FaIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (env('TWO_FACTOR_ATTENTICATION', true)) {

            $user = $request->user();
            if ($user && ! $user->two_factor_secret) {

                return redirect('2fa/index');
            }
        }

        return $next($request);
    }
}

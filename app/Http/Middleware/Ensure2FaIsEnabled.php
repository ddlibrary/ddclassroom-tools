<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Ensure2FaIsEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! optional($user)->two_factor_confirmed_at) {

            if ($request->hasSession()) {
                $request->session()->invalidate();
            }

            return redirect('/enable-two-factor-authentication');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\Dashboard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('dashboard_locked')) {
            /* Store the intended URL in the session. */
            if ($request->routeIs('dashboard.unlock') === false) {
                $request->session()->put('url.intended', url()->previous());
            }

            return redirect()->route('dashboard.unlock');
        }

        return $next($request);
    }
}

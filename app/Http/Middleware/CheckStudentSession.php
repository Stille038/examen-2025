<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next // check of studentnummer bestaat zo niet terug naar inlogpagina
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('studentnummer')) {
            return redirect('/'); // terug naar loginpagina
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $locale = $request->query('lang') ? $request->input('lang'): 'en';

         app()->setLocale($locale);

         return $next($request);

      //  $locale = $request->query('lang') ?? $request->query('locale');

    //   if (in_array($locale, config('translatable.locales')))
    //    {
    //         app()->setLocale($locale);
    //     }
    }
}

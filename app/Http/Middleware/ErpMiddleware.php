<?php

namespace App\Http\Middleware;

use App\Supports\ERP;
use Closure;

class ErpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));

        ERP::provideToScript([
            'locales'     => $locales,
            'indexLocale' => app()->getLocale()
        ]);
        return $next($request);
    }
}

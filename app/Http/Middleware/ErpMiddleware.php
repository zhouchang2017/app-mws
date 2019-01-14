<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Supports\ERP;
use Closure;
use Illuminate\Support\Facades\View;

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

        View::share('notifyTypes',User::getTypes());
        ERP::provideToScript([
            'locales'     => $locales,
            'indexLocale' => app()->getLocale(),
            'assetDomain' => $request->getScheme().'://'.config('filesystems.disks.qiniu.domains.default')
        ]);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class DomainMiddle
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
        $this->setViewVarDomain($request);
        return $next($request);
    }

    private function setViewVarDomain($request)
    {
        $host = $request->server('HTTP_HOST');
        $host = explode('.', $host);
        $subdomain = array_slice($host, -3, 1);
        View::share('domain', array_get($subdomain, 0, 'admin'));
    }
}

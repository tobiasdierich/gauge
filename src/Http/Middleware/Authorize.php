<?php

namespace TobiasDierich\Gauge\Http\Middleware;

use TobiasDierich\Gauge\Gauge;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Gauge::check($request) ? $next($request) : abort(403);
    }
}

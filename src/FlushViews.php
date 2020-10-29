<?php

namespace Laracasts\Matryoshka;

use Cache;

class FlushViews
{
    /**
     * Handle the request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     */
    public function handle($request, $next)
    {
        if (!config('matryoshka.cache_views')) {
            Cache::tags('views')->flush();
        }

        return $next($request);
    }
}


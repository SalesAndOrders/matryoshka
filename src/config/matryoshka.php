<?php

return [
    /**
     * A value for expiration time of cached views
     * in seconds, default 7 days
     */
    'matryoshka_cache_expire' => env('MATRYOSHKA_CACHE_EXPIRE', 604800),

    /**
     * If returns false then views won't be cached
     */
    'cache_views' => env('MATRYOSHKA_CACHE_VIEWS', true)
];

<?php

return [
    /**
     * A value for expiration time of cached views
     * in seconds, default 7 days
     */
    'matryoshka_cache_expire' => env('MATRYOSHKA_CACHE_EXPIRE', 604800),

    /**
     * On development set to true to not to cache views
     */
    'matryoshka_flush_cache' => env('MATRYOSHKA_FLUSH_CACHE', false)
];

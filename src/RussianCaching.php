<?php

namespace Laracasts\Matryoshka;

use Illuminate\Contracts\Cache\Repository as Cache;

class RussianCaching
{
    /**
     * The cache repository.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Create a new class instance.
     *
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Put to the cache.
     *
     * @param mixed  $key
     * @param string $fragment
     */
    public function put($key, $fragment)
    {
        $key = $this->normalizeCacheKey($key);
        return $this->cache
            ->tags('views')
            ->remember($key, config('matryoshka.cache_expire', 604800), function () use ($fragment) {
                return $fragment;
            });
    }

    /**
     * Check if the given key exists in the cache.
     *
     * @param mixed $key
     */
    public function has($key)
    {
        $key = $this->normalizeCacheKey($key);

        return $this->cache
            ->tags('views')
            ->has($key);
    }


    /**
     * Get key from the cache.
     *
     * @param mixed $key
     */
    public function get($key)
    {
        $key = $this->normalizeCacheKey($key);

        return $this->cache
            ->tags('views')
            ->get($key);
    }

    /**
     * Normalize the cache key.
     *
     * @param mixed $key
     */
    protected function normalizeCacheKey($key)
    {
        if (is_object($key) && method_exists($key, 'getCacheKey')) {
            return $key->getCacheKey();
        }

        return $key;
    }
}

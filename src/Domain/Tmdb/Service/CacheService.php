<?php

declare(strict_types = 1);

namespace MovieApi\Domain\Tmdb\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CacheService
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $key, callable $callbask): ?array
    {
        $cacheItem = $this->getCacheItem($key);
        if (!$cacheItem->isHit()) {
            $cacheItem = $this->set($key, $callbask());
        }

        return $cacheItem->get();
    }

    /**
     * @param mixed $data
     */
    public function set(string $key, $data): CacheItemInterface
    {
        $cacheItem = $this->getCacheItem($key);
        $cacheItem->set($data);
        $this->cache->save($cacheItem);

        return $cacheItem;
    }

    private function getCacheItem(string $key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }
}

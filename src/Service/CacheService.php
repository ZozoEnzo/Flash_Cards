<?php

namespace App\Service;

use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheService
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getCache(string $key)
    {
        return $this->cache->get($key, function (CacheItem $item) {
            return null; // Ou une valeur par défaut appropriée
        });
    }

    public function setCache(string $key, $value)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $this->cache->save($item);
    }
}
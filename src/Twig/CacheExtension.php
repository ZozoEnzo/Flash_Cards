<?php
// src/Twig/CacheExtension.php
namespace App\Twig;

use App\Service\CacheService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CacheExtension extends AbstractExtension
{
    private $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_cache', [$this, 'getCache']),
        ];
    }

    public function getCache(string $key)
    {
        return $this->cacheService->getCache($key);
    }
}
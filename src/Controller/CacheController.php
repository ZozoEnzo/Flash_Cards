<?php

namespace App\Controller;

use App\Service\CacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    protected $cacheService;

    /**
     * CacheController constructor.
     * @param $cacheService
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }


    /**
     * @Route("/flashcard/cache/add", name="app_cache_post")
     */
    public function deleteCategory(Request $request): Response
    {
        $key = $request->request->get('key');
        $value = $request->request->get('value');
        try {
            $this->cacheService->setCache($key, $value);
            return $this->json(['code'=> 200, 'message' => 'Upade du '.$key],200);
        } catch (\Exception $exception) {
            return $this->json(['code'=> 403, 'message' => 'Error lors de l\'update du '.$key],403);
        }
    }
}
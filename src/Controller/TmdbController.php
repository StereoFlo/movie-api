<?php

declare(strict_types = 1);

namespace MovieApi\Controller;

use InvalidArgumentException;
use MovieApi\Domain\Tmdb\Service\TmdbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TmdbController extends AbstractController
{
    /**
     * @var TmdbService
     */
    private $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * @Route("/movie/{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovie($id);

        return $this->json($movie);
    }

    /**
     * @Route("/movie/{id}/images", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function getMovieImages(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovieImages($id);

        return $this->json($movie);
    }

    /**
     * @Route("/person/{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function getPerson(int $id): JsonResponse
    {
        $person = $this->tmdbService->getPerson($id);

        return $this->json($person);
    }

    /**
     * @Route("/search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('query');
        $page  = $request->get('page', '1');

        if (empty($query) || $page < 1 || $page > 1000) {
            throw new InvalidArgumentException('query must be specified');
        }

        $results = $this->tmdbService->search($query, $page);

        return $this->json($results);
    }

    /**
     * @Route("/trending", methods={"GET"})
     */
    public function getTrending(Request $request): JsonResponse
    {
        $page  = $request->get('page', '1');
        $results = $this->tmdbService->getTrending($page);

        return $this->json($results);
    }
}

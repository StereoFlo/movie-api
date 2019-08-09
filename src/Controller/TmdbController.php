<?php

namespace Controller;

use Domain\Tmdb\Model\TmdbModel;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class DefaultController
 * @package Controller
 */
class TmdbController extends AbstractController
{
    /**
     * @var TmdbModel
     */
    private $tmdbModel;

    /**
     * DefaultController constructor.
     * @param TmdbModel $tmdbModel
     */
    public function __construct(TmdbModel $tmdbModel)
    {
        $this->tmdbModel = $tmdbModel;
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->tmdbModel->getMovie($id);
        return $this->json($movie);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovieImages(int $id): JsonResponse
    {
        $movie = $this->tmdbModel->getMovieImages($id);
        return $this->json($movie);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPerson(int $id): JsonResponse
    {
        $person = $this->tmdbModel->getPerson($id);
        return $this->json($person);
    }
}

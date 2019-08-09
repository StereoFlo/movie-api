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
class DefaultController extends AbstractController
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
     * @return JsonResponse
     * @throws ReflectionException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function index(): JsonResponse
    {
        $movie = $this->tmdbModel->getMovie(2502);
        return $this->json($movie);
    }
}

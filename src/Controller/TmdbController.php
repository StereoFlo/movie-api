<?php

namespace Controller;

use Domain\Tmdb\Model\TmdbModel;
use ReflectionException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var Request|null
     */
    private $request;

    /**
     * DefaultController constructor.
     * @param RequestStack $requestStack
     * @param TmdbModel $tmdbModel
     */
    public function __construct(RequestStack $requestStack, TmdbModel $tmdbModel)
    {
        $this->tmdbModel = $tmdbModel;
        $this->request = $requestStack->getCurrentRequest();
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

    /**
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function search(): JsonResponse
    {
        $query = $this->request->get('query');
        $page  = $this->request->get('page', 1);
        if (empty($query) || $page < 1 || $page > 1000) {
            throw new RuntimeException('query must be specified');
        }
        $results = $this->tmdbModel->search($query, $page);
        return $this->json($results);
    }
}

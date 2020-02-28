<?php

namespace Controller;

use Application\Exception\ModelNotFoundException;
use Domain\Tmdb\Model\TmdbModel;
use ReflectionException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @Route("/movie/{id}", requirements={"id":"\d+"}, methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ModelNotFoundException
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->tmdbModel->getMovie($id);
        return $this->json($movie);
    }

    /**
     * @Route("/movie/{id}/images", requirements={"id":"\d+"}, methods={"GET"})
     *
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
     * @Route("/person/{id}", requirements={"id":"\d+"}, methods={"GET"})
     *
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
     * @Route("/search", methods={"GET"})
     *
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

    /**
     * @Route("/trending", methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getTrending(): JsonResponse
    {
        $results = $this->tmdbModel->getTrending();
        return $this->json($results);
    }
}

<?php

namespace Controller;

use RuntimeException;
use Stereoflo\TmdbBundle\Service;
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
use TMDB\Exception\EmptyQueryParamException;
use TMDB\Exception\InvalidParamException;
use TMDB\Section\Movies\Images;
use TMDB\Section\Movies\MovieDetails;
use TMDB\Section\People\Person;
use TMDB\Section\Search\Movie;
use TMDB\Section\Trending\Trending;

class TmdbController extends AbstractController
{
    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var Service
     */
    private $service;

    /**
     * DefaultController constructor.
     * @param RequestStack $requestStack
     * @param Service $service
     */
    public function __construct(RequestStack $requestStack, Service $service)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->service = $service;
    }

    /**
     * @Route("/movie/{id}", requirements={"id":"\d+"}, methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->service->get(new MovieDetails(null, [$id]));

        return $this->json($movie);
    }

    /**
     * @Route("/movie/{id}/images", requirements={"id":"\d+"}, methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovieImages(int $id): JsonResponse
    {
        $movie = $this->service->get(new Images(null, [$id]));

        return $this->json($movie);
    }

    /**
     * @Route("/person/{id}", requirements={"id":"\d+"}, methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPerson(int $id): JsonResponse
    {
        $person = $this->service->get(new Person(null, [$id]));

        return $this->json($person);
    }

    /**
     * @Route("/search", methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
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

        $results = $this->service->get(new Movie(null, ['page', $page], ['query' => $query]));

        return $this->json($results);
    }

    /**
     * @Route("/trending", methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getTrending(): JsonResponse
    {
        $results = $this->service->get(new Trending());

        return $this->json($results);
    }
}

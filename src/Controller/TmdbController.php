<?php

declare(strict_types = 1);

namespace MovieApi\Controller;

use InvalidArgumentException;
use MovieApi\Domain\Tmdb\Service\TmdbService;
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
use TMDB\Exception\EmptyQueryParamException;
use TMDB\Exception\InvalidParamException;

class TmdbController extends AbstractController
{
    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var TmdbService
     */
    private $tmdbService;

    public function __construct(RequestStack $requestStack, TmdbService $tmdbService)
    {
        $this->setRequest($requestStack);
        $this->tmdbService = $tmdbService;
    }

    /**
     * @Route("/movie/{id}", requirements={"id":"\d+"}, methods={"GET"})
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovie($id);

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getMovieImages(int $id): JsonResponse
    {
        $movie = $this->tmdbService->getMovieImages($id);

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPerson(int $id): JsonResponse
    {
        $person = $this->tmdbService->getPerson($id);

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function search(): JsonResponse
    {
        $query = $this->request->get('query');
        $page  = $this->request->get('page', 1);

        if (empty($query) || $page < 1 || $page > 1000) {
            throw new InvalidArgumentException('query must be specified');
        }

        $results = $this->tmdbService->search($query, $page);

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getTrending(): JsonResponse
    {
        $results = $this->tmdbService->getTrending();

        return $this->json($results);
    }

    private function setRequest(RequestStack $requestStack): void
    {
        if (null === $requestStack->getCurrentRequest()) {
            throw new RuntimeException('current request is empty');
        }

        $this->request = $requestStack->getCurrentRequest();
    }
}

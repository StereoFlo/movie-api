<?php

declare(strict_types = 1);

namespace MovieApi\Domain\Tmdb\Service;

use function md5;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Stereoflo\TmdbBundle\Service;
use Symfony\Contracts\Cache\CacheInterface;
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

class TmdbService
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(Service $service, CacheInterface $cache)
    {
        $this->service = $service;
        $this->cache   = $cache;
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function getMovie(int $id): array
    {
        if (!$this->getCache('movie_' . $id)) {
            $this->setCache('movie_' . $id, $this->service->get(new MovieDetails(null, [$id])));
        }

        return $this->getCache('movie_' . $id);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidArgumentException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function getMovieImages(int $id): array
    {
        if (!$this->getCache('images_' . $id)) {
            $this->setCache('images_' . $id, $this->service->get(new Images(null, [$id])));
        }

        return $this->getCache('images_' . $id);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function getPerson(int $id): array
    {
        if (!$this->getCache('person_' . $id)) {
            $this->setCache('person_' . $id, $this->service->get(new Person(null, [$id])));
        }

        return $this->getCache('person_' . $id);
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function search(string $query, string $page = '1'): array
    {
        if (!$this->getCache(md5($query . $page))) {
            $this->setCache(md5($query . $page), $this->service->get(new Movie(null, ['page', $page], ['query' => $query])));
        }

        return $this->getCache(md5($query . $page));
    }

    /**
     * @throws DecodingExceptionInterface
     * @throws EmptyQueryParamException
     * @throws InvalidParamException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function getTrending(): array
    {
        if (!$this->getCache('trending')) {
            $this->setCache('trending', $this->service->get(new Trending(null, ['all', 'week'])));
        }

        return $this->getCache('trending');
    }

    /**
     * @param mixed $key
     *
     * @throws InvalidArgumentException
     *
     * @return array<string, mixed>|null
     */
    private function getCache($key): ?array
    {
        $key = (string) $key;
        /** @var CacheItemInterface $cacheItem */
        $cacheItem = $this->cache->getItem($key);
        if (!$cacheItem->isHit()) {
            return null;
        }

        return $cacheItem->get();
    }

    /**
     * @param mixed $key
     * @param mixed $data
     *
     * @throws InvalidArgumentException
     */
    private function setCache($key, $data): void
    {
        $key = (string) $key;
        /** @var CacheItemInterface $cacheItem */
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->set($data);
        $this->cache->save($cacheItem);
    }
}

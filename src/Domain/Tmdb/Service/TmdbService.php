<?php

declare(strict_types = 1);

namespace MovieApi\Domain\Tmdb\Service;

use TMDB\Section\Tv\TvDetails;
use function md5;
use Stereoflo\TmdbBundle\Service;
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
     * @var CacheService
     */
    private $cacheService;

    public function __construct(Service $service, CacheService $cacheService)
    {
        $this->service      = $service;
        $this->cacheService = $cacheService;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMovie(int $id): array
    {
        return $this->cacheService->get('movie_' . $id, function () use ($id) {
            return $this->service->get(new MovieDetails(null, [$id]));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function getTv(int $id): array
    {
        return $this->cacheService->get('tv_' . $id, function () use ($id) {
            return $this->service->get(new TvDetails(null, [$id]));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function getMovieImages(int $id): array
    {
        return $this->cacheService->get('images_' . $id, function () use ($id) {
            return $this->service->get(new Images(null, [$id]));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function getPerson(int $id): array
    {
        return $this->cacheService->get('person_' . $id, function () use ($id) {
            return $this->service->get(new Person(null, [$id]));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function search(string $query, string $page = '1'): array
    {
        return $this->cacheService->get(md5($query . $page), function () use ($query, $page) {
            return $this->service->get(new Movie(null, ['page', $page], ['query' => $query]));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function getTrending(string $page = '1'): array
    {
        return $this->cacheService->get('trending_' . $page, function () use ($page) {
            return $this->service->get(new Trending(null, ['all', 'week'], ['page' => $page]));
        });
    }
}

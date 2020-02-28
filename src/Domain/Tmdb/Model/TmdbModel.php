<?php

namespace Domain\Tmdb\Model;

use Application\Exception\ModelNotFoundException;
use Exception;
use ReflectionClass;
use ReflectionException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use TmdbApi\Common;
use TmdbApi\Query;
use TmdbApi\Section\AbstractSection;
use TmdbApi\Section\Movie\Images;
use TmdbApi\Section\Movie\Movie;
use TmdbApi\Section\People\Person;
use TmdbApi\Section\Trending\Trending;
use TmdbApi\TmdbApi;

class TmdbModel
{
    /**
     * @var Common
     */
    private $common;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var AbstractSection
     */
    private $section;

    /**
     * TmdbModel constructor.
     * @param Common $common
     */
    public function __construct(Common $common)
    {
        $this->common = $common;
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getTrending(): array
    {
        return $this->init(Trending::class, '')->get();
    }

    /**
     * @param int $id
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPerson(int $id): array
    {
        return $this->init(Person::class, $id)->get();
    }

    /**
     * @param int $id
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ModelNotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovie(int $id): array
    {
        try {
            return $this->init(Movie::class, $id)->get();
        } catch (Exception $exception) {
            throw new ModelNotFoundException('movie doesnt found');
        }
    }

    /**
     * @param int $id
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovieImages(int $id): array
    {
        return $this->init(Images::class, $id)->get();
    }

    /**
     * @param string $query
     * @param int $page
     *
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function search(string $query, int $page): array
    {
        return $this->init(\TmdbApi\Section\Search\Movie::class, $query, ['page' => $page])->get();
    }

    /**
     * @param string $class
     * @param $query
     * @param array|null $additional
     *
     * @return TmdbApi
     *
     * @throws ReflectionException
     */
    private function init(string $class, $query, array $additional = []): TmdbApi
    {
        $refClass = new ReflectionClass($class);
        $this->query = new Query($query, true, ['language' => 'ru'] + $additional);
        $this->section = $refClass->newInstance($this->query);

        return new TmdbApi($this->common, $this->section);
    }
}

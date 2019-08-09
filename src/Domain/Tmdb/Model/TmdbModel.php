<?php

namespace Domain\Tmdb\Model;

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
use TmdbApi\TmdbApi;

/**
 * Class TmdbModel
 * @package Domain\Tmdb\Model
 */
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
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ReflectionException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMovie(int $id): array
    {
        return $this->init(Movie::class, $id)->get();
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
    public function getMovieImages(int $id): array
    {
        return $this->init(Images::class, $id)->get();
    }

    /**
     * @param string $class
     * @param $query
     * @return TmdbApi
     * @throws ReflectionException
     */
    private function init(string $class, $query): TmdbApi
    {
        $refClass = new ReflectionClass($class);
        $this->query = new Query($query, true, ['language' => 'ru']);
        $this->section = $refClass->newInstance($this->query);
        return new TmdbApi($this->common, $this->section);
    }
}

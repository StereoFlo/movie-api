<?php

declare(strict_types = 1);

namespace MovieApi\Application\Listener;

use MovieApi\Application\Exception\ModelNotFoundException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionListener
{
    /**
     * @var ExceptionEvent
     */
    private $event;

    /**
     * @var string
     */
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $this->event = $event;
        $e           = $event->getThrowable();

        switch (true) {
            case $e instanceof UnauthorizedHttpException:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], $e->getCode() > 0 ? $e->getCode() : 401));
                break;
            case $e instanceof NotFoundHttpException:
            case $e instanceof ModelNotFoundException:
                $event->setResponse($this->getResponse(['error' => 'Not found'], 404));
                break;
            case $e instanceof HttpException:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], $e->getCode()));
                break;
            case $e instanceof RuntimeException:
            default:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], 500));
                break;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getResponse(array $data, int $status = 200): Response
    {
        return new JsonResponse($data, $status);
    }
}

<?php

namespace Application\Listener;

use Application\Exception\ModelNotFoundException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class ExceptionListener
 * @package Application\Listener
 */
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

    /**
     * ExceptionListener constructor.
     *
     * @param string              $environment
     */
    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $this->event = $event;
        $e = $event->getException();

        switch (true) {
            case $e instanceof UnauthorizedHttpException:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], $e->getCode() > 0 ? $e->getCode() : 401));
                break;
            case $e instanceof NotFoundHttpException:
            case $e instanceof ModelNotFoundException:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], 404));
                break;
            case $e instanceof RuntimeException:
            default:
                $event->setResponse($this->getResponse(['error' => $e->getMessage()], 500));
                break;
        }
    }

    /**
     * @param array $data
     * @param int $status
     *
     * @return Response
     */
    private function getResponse(array $data, int $status = 200): Response
    {
        return new JsonResponse($data, $status);
    }
}

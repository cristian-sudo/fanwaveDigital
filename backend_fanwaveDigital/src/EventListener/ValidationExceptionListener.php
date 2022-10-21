<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationException) {
            return;
        }

        $violations = $exception->getViolations();

        foreach ($violations as &$violation) {
            $violation['propertyPath'] = $violation['propertyPath'] === '' ? '__root__' : $violation['propertyPath'];
        }

        $event->setResponse(new JsonResponse(
            [
                'title' => 'An error occurred',
                'detail' => $exception->getMessage(),
                'violations' => $violations,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}

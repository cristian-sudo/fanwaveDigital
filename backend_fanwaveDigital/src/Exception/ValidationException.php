<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    public function __construct(private ConstraintViolationListInterface $violations)
    {
        $message = '';

        foreach ($violations as $violation) {
            $message .= sprintf('"%s": %s', $violation->getPropertyPath(), (string) $violation->getMessage());
        }

        parent::__construct($message);
    }

    /** @return array<int, array{propertyPath: string, message: string}>*/
    public function getViolations(): array
    {
        $violations = [];

        foreach ($this->violations as $violation) {
            $violations[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => (string) $violation->getMessage(),
                'code' => $violation->getCode(),
            ];
        }

        return $violations;
    }
}

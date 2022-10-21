<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\ValidationException;

use function count;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validate an object and throws an exception if there are any violations.
 */
class Validator
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @param Constraint|array<array-key, Constraint>|null $constraints
     * @param string|GroupSequence|array<string|GroupSequence>|null $groups
     */
    public function validate(mixed $value, Constraint|array $constraints = null, string|GroupSequence|array $groups = null): void
    {
        $violations = $this->validator->validate($value, $constraints);

        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }
    }
}

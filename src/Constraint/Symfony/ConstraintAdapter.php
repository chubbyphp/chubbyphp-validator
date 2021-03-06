<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class ConstraintAdapter implements ConstraintInterface
{
    private Constraint $symfonyConstraint;

    private ConstraintValidatorInterface $symfonyConstraintValidator;

    public function __construct(Constraint $symfonyConstraint, ConstraintValidatorInterface $symfonyConstraintValidator)
    {
        $this->symfonyConstraint = $symfonyConstraint;
        $this->symfonyConstraintValidator = $symfonyConstraintValidator;
    }

    /**
     * @param mixed $value
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
    ) {
        $executionContext = new ExecutionContext($path, $value, $context);

        $this->symfonyConstraintValidator->initialize($executionContext);
        $this->symfonyConstraintValidator->validate($value, $this->symfonyConstraint);

        return $executionContext->getErrors();
    }
}

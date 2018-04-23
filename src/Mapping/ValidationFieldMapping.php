<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationFieldMapping implements ValidationFieldMappingInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param string                $name
     * @param ConstraintInterface[] $constraints
     * @param array                 $groups
     * @param AccessorInterface     $accessor
     */
    public function __construct(string $name, array $constraints, array $groups, AccessorInterface $accessor)
    {
        $this->name = $name;
        $this->constraints = $constraints;
        $this->groups = $groups;
        $this->accessor = $accessor;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return AccessorInterface
     */
    public function getAccessor(): AccessorInterface
    {
        return $this->accessor;
    }
}

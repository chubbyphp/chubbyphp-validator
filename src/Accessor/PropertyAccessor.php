<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;
use Doctrine\Persistence\Proxy;

final class PropertyAccessor implements AccessorInterface
{
    /**
     * @var string
     */
    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getValue($object)
    {
        $class = $this->getClass($object);

        if (!property_exists($class, $this->property)) {
            throw ValidatorLogicException::createMissingProperty($class, $this->property);
        }

        $getter = \Closure::bind(
            function ($property) {
                return $this->{$property};
            },
            $object,
            $class
        );

        return $getter($this->property);
    }

    /**
     * @param object $object
     */
    private function getClass($object): string
    {
        if (interface_exists('Doctrine\Persistence\Proxy') && $object instanceof Proxy) {
            if (!$object->__isInitialized()) {
                $object->__load();
            }

            /** @var \ReflectionClass $parentReflectionClass */
            $parentReflectionClass = (new \ReflectionClass($object))->getParentClass();

            return $parentReflectionClass->getName();
        }

        return get_class($object);
    }
}

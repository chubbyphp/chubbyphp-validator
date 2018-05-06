<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Validator;

use Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistry;
use Chubbyphp\Validation\ValidatorLogicException;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
use Chubbyphp\Tests\Validation\Resources\Model\AbstractManyModel;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistry
 */
class ValidatorObjectMappingRegistryTest extends TestCase
{
    public function testGetObjectMapping()
    {
        $object = $this->getObject();

        $registry = new ValidatorObjectMappingRegistry([
            $this->getValidationObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping(get_class($object));

        self::assertInstanceOf(ValidationObjectMappingInterface::class, $mapping);
    }

    public function testGetMissingObjectMapping()
    {
        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage('There is no mapping for class: "stdClass"');

        $registry = new ValidatorObjectMappingRegistry([]);

        $registry->getObjectMapping(get_class(new \stdClass()));
    }

    public function testGetObjectMappingFromDoctrineProxy()
    {
        $object = $this->getProxyObject();

        $registry = new ValidatorObjectMappingRegistry([
            $this->getValidationProxyObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping(get_class($object));

        self::assertInstanceOf(ValidationObjectMappingInterface::class, $mapping);
    }

    /**
     * @return ValidationObjectMappingInterface
     */
    private function getValidationObjectMapping(): ValidationObjectMappingInterface
    {
        /** @var ValidationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(ValidationObjectMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return get_class($object);
            }
        );

        return $objectMapping;
    }

    /**
     * @return ValidationObjectMappingInterface
     */
    private function getValidationProxyObjectMapping(): ValidationObjectMappingInterface
    {
        /** @var ValidationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(ValidationObjectMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getProxyObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return AbstractManyModel::class;
            }
        );

        return $objectMapping;
    }

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @return string|null
             */
            public function getName()
            {
                return $this->name;
            }

            /**
             * @param string $name
             *
             * @return self
             */
            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }

    /**
     * @return object
     */
    private function getProxyObject()
    {
        return new class() extends AbstractManyModel implements Proxy {
            /**
             * Initializes this proxy if its not yet initialized.
             *
             * Acts as a no-op if already initialized.
             */
            public function __load()
            {
            }

            /**
             * Returns whether this proxy is initialized or not.
             *
             * @return bool
             */
            public function __isInitialized()
            {
            }
        };
    }
}
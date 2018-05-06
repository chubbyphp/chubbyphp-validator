<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Error;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Error\NestedErrorMessages;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Error\NestedErrorMessages
 */
final class NestedErrorMessagesTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testWithoutMessages()
    {
        $errorMessages = new NestedErrorMessages([], function (string $key, array $arguments) { return $key; });

        self::assertEquals([], $errorMessages->getMessages());
    }

    public function testWithMessages()
    {
        $errors = [
            $this->getError('collection[_all]', 'constraint.collection.all'),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint1'),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint2'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint1'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint2'),
        ];

        $errorMessages = new NestedErrorMessages(
            $errors,
            function (string $key, array $arguments) { return $key; }
        );

        self::assertEquals([
            'collection' => [
                '_all' => [
                    'constraint.collection.all',
                ],
                0 => [
                    'field1' => [
                        'constraint.collection0.constraint1',
                        'constraint.collection0.constraint2',
                    ],
                ],
                1 => [
                    'field1' => [
                        'constraint.collection1.constraint1',
                        'constraint.collection1.constraint2',
                    ],
                ],
            ],
        ], $errorMessages->getMessages());
    }

    /**
     * @param string $path
     * @param string $key
     * @param array  $arguments
     *
     * @return ErrorInterface
     */
    private function getError(string $path, string $key, array $arguments = []): ErrorInterface
    {
        /** @var ErrorInterface|MockObject $error */
        $error = $this->getMockForInterface(ErrorInterface::class, [
            'getPath' => [['return' => $path]],
            'getKey' => [['return' => $key]],
            'getArguments' => [['return' => $arguments]],
        ]);

        return $error;
    }
}

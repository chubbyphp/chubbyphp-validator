<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\NumericConstraint
 */
final class NumericConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', null));
    }

    public function testWithInteger()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1));
    }

    public function testWithFloat()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1.1));
    }

    public function testWithString()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', '1.1'));
    }

    public function testWithoutNumeric()
    {
        $constraint = new NumericConstraint();

        $error = new Error('numeric', 'constraint.numeric.notnumeric', ['input' => 'test']);

        self::assertEquals([$error], $constraint->validate('numeric', 'test'));
    }
}

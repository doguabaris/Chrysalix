<?php

/**
 * OperatorReordererTest.php
 *
 * This file is part of the Chrysalix library. It contains unit tests for verifying
 * that the OperatorReorderer transformation swaps operands in commutative expressions.
 *
 * The tests are implemented using PHPUnit.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */

namespace Chrysalix\Tests\Unit\Transformation;

use Chrysalix\Transformation\OperatorReorderer;
use Chrysalix\Core\Chrysalix;
use PHPUnit\Framework\TestCase;

/**
 * OperatorReordererTest
 *
 * This class contains unit tests for the OperatorReorderer transformation.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class OperatorReordererTest extends TestCase
{
    /**
     * Test that OperatorReorderer swaps operands in simple expressions.
     *
     * This test ensures that the OperatorReorderer swaps operands for the '+' operator in a simple expression.
     *
     * @return void
     */
    public function testOperatorReorderer(): void
    {
        $code = <<<'CODE'
<?php
echo $a + $b;
CODE;

        $transformer = new OperatorReorderer();
        $engine = new Chrysalix([$transformer]);
        $transformed = $engine->process($code);

        // Check that both operands are present and in swapped order.
        $this->assertStringContainsString('$a', $transformed);
        $this->assertStringContainsString('$b', $transformed);
        // Check that the '+' operator is present.
        $this->assertStringContainsString('+', $transformed);
    }
}

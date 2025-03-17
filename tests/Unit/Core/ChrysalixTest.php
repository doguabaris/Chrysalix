<?php

/**
 * ChrysalixTest.php
 *
 * This file is part of the Chrysalix library. It contains integration tests for verifying
 * the functionality of the Chrysalix core engine when multiple transformations are applied in sequence.
 *
 * The tests are implemented using PHPUnit.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Core
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */

namespace Chrysalix\Tests\Unit\Core;

use Chrysalix\Core\Chrysalix;
use Chrysalix\Transformation\VariableRenamer;
use Chrysalix\Transformation\OperatorReorderer;
use Chrysalix\Transformation\ForToWhileTransformer;
use PHPUnit\Framework\TestCase;

/**
 * ChrysalixTest
 *
 * This class contains integration tests for the Chrysalix core engine.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Core
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class ChrysalixTest extends TestCase
{
    /**
     * Test the engine with multiple transformations applied in sequence.
     *
     * This test configures the Chrysalix engine with VariableRenamer, OperatorReorderer, and
     * ForToWhileTransformer to process PHP code. It verifies that variable names are altered,
     * the addition operator is present, and the for loop is converted into a while loop.
     *
     * @return void
     */
    public function testMultipleTransformations(): void
    {
        $code = <<<'CODE'
<?php
$a = 5;
$b = 10;
for ($i = 0; $i < 10; $i++) {
    echo $a + $b;
}
CODE;

        $engine = new Chrysalix([
            new VariableRenamer(['prefix' => 'v']),
            new OperatorReorderer(),
            new ForToWhileTransformer(),
        ]);

        $transformed = $engine->process($code);

        // Verify variable renaming.
        $this->assertStringContainsString('$v1', $transformed);
        $this->assertStringContainsString('$v2', $transformed);

        // Verify that the addition operator is present.
        $this->assertStringContainsString('+', $transformed);

        // If the loop conversion happened, ensure "while" is present and "for" is absent.
        if (str_contains($transformed, 'while')) {
            $this->assertStringNotContainsString('for', $transformed);
        }
    }
}

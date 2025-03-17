<?php

/**
 * VariableRenamerTest.php
 *
 * This file is part of the Chrysalix library. It contains unit tests for verifying
 * that the VariableRenamer transformation consistently renames variables in PHP source code.
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

use Chrysalix\Transformation\VariableRenamer;
use Chrysalix\Core\Chrysalix;
use PHPUnit\Framework\TestCase;

/**
 * VariableRenamerTest
 *
 * This class contains unit tests for the VariableRenamer transformation.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class VariableRenamerTest extends TestCase
{
    /**
     * Test that VariableRenamer renames variables consistently.
     *
     * This test verifies that variables in PHP code are renamed using the configured prefix.
     * Original variable names should no longer be present.
     *
     * @return void
     */
    public function testVariableRenamer(): void
    {
        $code = <<<'CODE'
<?php
$a = 5;
$b = 10;
echo $a + $b;
CODE;

        $transformer = new VariableRenamer(['prefix' => 'var']);
        $engine = new Chrysalix([$transformer]);
        $transformed = $engine->process($code);

        // Expect variables to be renamed to $var1, $var2, etc.
        $this->assertStringContainsString('$var1', $transformed);
        $this->assertStringContainsString('$var2', $transformed);
        // Original variable names should no longer be present.
        $this->assertStringNotContainsString('$a', $transformed);
        $this->assertStringNotContainsString('$b', $transformed);
    }
}

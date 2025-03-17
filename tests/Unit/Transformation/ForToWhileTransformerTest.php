<?php

/**
 * ForToWhileTransformerTest.php
 *
 * This file is part of the Chrysalix library. It contains unit tests for verifying
 * that the ForToWhileTransformer correctly converts simple for loops into while loops.
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

use Chrysalix\Transformation\ForToWhileTransformer;
use Chrysalix\Core\Chrysalix;
use PHPUnit\Framework\TestCase;

/**
 * ForToWhileTransformerTest
 *
 * This class contains unit tests for the ForToWhileTransformer transformation.
 *
 * @category Test
 * @package  Chrysalix\Tests\Unit\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class ForToWhileTransformerTest extends TestCase
{
    /**
     * Test that ForToWhileTransformer converts a for loop into a while loop.
     *
     * This test verifies that the ForToWhileTransformer transforms a simple for loop into a while loop.
     *
     * @return void
     */
    public function testForToWhileTransformer(): void
    {
        $code = <<<'CODE'
<?php
for ($i = 0; $i < 10; $i++) {
    echo $i;
}
CODE;

        $transformer = new ForToWhileTransformer();
        $engine = new Chrysalix([$transformer]);
        $transformed = $engine->process($code);

        // Expect "while" to appear in the transformed code and "for" to be absent.
        $this->assertStringContainsString('while', $transformed);
        $this->assertStringNotContainsString('for', $transformed);
    }
}

<?php

/**
 * ForToWhileTransformer.php
 *
 * This file is part of the Chrysalix library, a PHP library for metamorphic
 * code transformations. It provides the ForToWhileTransformer class which
 * converts simple for loops into equivalent while loops. This experimental
 * transformation expects for loops in the form:
 *   for (init; condition; increment) { body }
 * and converts them into:
 *   { init; while (condition) { body; increment; } }
 * Note: This transformer does not handle nested or complex for loops.
 *
 * @category Transformation
 * @package  Chrysalix\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */

namespace Chrysalix\Transformation;

use Chrysalix\Contract\ITransformation;

/**
 * ForToWhileTransformer
 *
 * This class converts simple for loops into equivalent while loops using a token-based approach.
 * It transforms for loops of the form:
 *
 *      for (init; condition; increment) { body }
 *
 * into:
 *
 *      { init; while (condition) { body; increment; } }
 *
 *
 * Note: This transformation is experimental and is intended only for simple loop constructs.
 *
 * @category Transformation
 * @package  Chrysalix\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class ForToWhileTransformer implements ITransformation
{
    /**
     * Transforms tokens by converting a for loop into a while loop.
     *
     * This method scans the given token array for a for loop in the format:
     *
     *      for (init; condition; increment) { body }
     *
     * It extracts the initialization, condition, and increment parts, then
     * wraps the loop body in a while loop. The final output is:
     *
     *      { init; while (condition) { body; increment; } }
     *
     * This transformation is experimental and works only for simple loops.
     *
     * @since 0.1.0
     *
     * @param array<int, array{0: int, 1: string, 2?: int}|string> $tokens
     *        An array of tokens.
     *
     * @return array<int, array{0: int, 1: string, 2?: int}|string>
     *         The transformed tokens.
     */
    public function transform(array $tokens): array
    {
        $transformedTokens = [];
        $length = count($tokens);
        $i = 0;

        $skipWhitespace = function () use ($tokens, &$i, $length): void {
            while ($i < $length && is_array($tokens[$i]) && $tokens[$i][0] === T_WHITESPACE) {
                $i++;
            }
        };

        while ($i < $length) {
            // Detect a for loop by checking for T_FOR
            if (is_array($tokens[$i]) && $tokens[$i][0] === T_FOR) {
                // Move past the 'for' token
                $i++;
                $skipWhitespace();

                // Ensure the next token is '('
                if ($tokens[$i] === '(') {
                    $i++;
                    $skipWhitespace();
                    $init = '';
                    $condition = '';
                    $increment = '';

                    // Extract initialization (up to first semicolon)
                    while ($i < $length && $tokens[$i] !== ';') {
                        $init .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                        $i++;
                    }
                    $i++; // Skip semicolon
                    $skipWhitespace();

                    // Extract condition (up to next semicolon)
                    while ($i < $length && $tokens[$i] !== ';') {
                        $condition .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                        $i++;
                    }
                    $i++; // Skip semicolon
                    $skipWhitespace();

                    // Extract increment (up to closing parenthesis)
                    while ($i < $length && $tokens[$i] !== ')') {
                        $increment .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                        $i++;
                    }
                    $i++; // Skip closing parenthesis
                    $skipWhitespace();

                    // Expect a block starting with '{'
                    if ($tokens[$i] === '{') {
                        // Build a new token sequence representing:
                        // { init; while (condition) { body; increment; } }
                        $newTokens = [];
                        $newTokens[] = '{';

                        // Append initialization and a semicolon
                        $newTokens[] = $init;
                        $newTokens[] = ';';

                        // Append 'while' keyword and condition in parentheses
                        $newTokens[] = 'while';
                        $newTokens[] = '(';
                        $newTokens[] = $condition;
                        $newTokens[] = ')';
                        $newTokens[] = '{';

                        // Move past the opening '{'
                        $i++;
                        $skipWhitespace();
                        $braceCount = 1;
                        $bodyTokens = [];

                        // Collect tokens for the loop body until the matching '}'
                        while ($i < $length && $braceCount > 0) {
                            if ($tokens[$i] === '{') {
                                $braceCount++;
                            } elseif ($tokens[$i] === '}') {
                                $braceCount--;
                            }
                            if ($braceCount > 0) {
                                $bodyTokens[] = $tokens[$i];
                            }
                            $i++;
                        }

                        // Append the body tokens
                        $newTokens = array_merge($newTokens, $bodyTokens);

                        // Append increment and a semicolon before closing the while block
                        $newTokens[] = ';';
                        $newTokens[] = $increment;

                        // Close the while loop block and the outer block
                        $newTokens[] = '}';
                        $newTokens[] = '}';

                        // Add the new tokens to the transformed output
                        foreach ($newTokens as $t) {
                            if (is_array($t)) {
                                // Since offset 1 always exists, simply use it.
                                $transformedTokens[] = $t[1];
                            } else {
                                $transformedTokens[] = $t;
                            }
                        }
                        continue; // Proceed with the next token after the transformed for loop.
                    }
                }
            }
            // If not transforming, simply append the token as is.
            $transformedTokens[] = $tokens[$i];
            $i++;
        }

        return $transformedTokens;
    }
}

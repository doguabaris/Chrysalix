<?php

/**
 * OperatorReorderer.php
 *
 * This file is part of the Chrysalix library, a PHP library for metamorphic
 * code transformations. It provides the OperatorReorderer class, which
 * implements an experimental transformation that swaps operands for commutative
 * operators (e.g., addition and multiplication) in simple binary expressions.
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
 * OperatorReorderer
 *
 * This experimental class implements a transformation that swaps the operands for commutative operators,
 * such as addition (+) and multiplication (*), in simple binary expressions.
 *
 * Warning: This is a naive implementation intended for basic cases only.
 *
 * @category Transformation
 * @package  Chrysalix\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class OperatorReorderer implements ITransformation
{
    /**
     * Transforms tokens by swapping operands for commutative operators.
     *
     * This method scans the token array for patterns of the form:
     * "operand, operator, operand". If the operator is '+' or '*', and both
     * operands are token arrays, the method swaps the operands to produce an
     * equivalent expression.
     *
     * Note: This transformation is naive and is intended only for simple
     * binary expressions.
     *
     * @since 0.1.0
     *
     * @param array<int, array{0: int, 1: string, 2?: int}|string> $tokens An
     *        array of tokens representing PHP source code.
     *
     * @return array<int, array{0: int, 1: string, 2?: int}|string> The
     *         transformed tokens.
     */
    public function transform(array $tokens): array
    {
        $transformedTokens = [];
        $length = count($tokens);
        $i = 0;

        while ($i < $length) {
            // Look ahead for pattern: operand, operator, operand
            if ($i > 0 && $i < $length - 1) {
                $prev = $tokens[$i - 1];
                $curr = $tokens[$i];
                $next = $tokens[$i + 1];

                // Process addition operator: '+'
                if ($curr === '+' && is_array($prev) && is_array($next)) {
                    // Swap operands: output next operand, then '+', then previous operand.
                    // Replace the last token added (previous operand) with the next operand.
                    $transformedTokens[count($transformedTokens) - 1] = $next;
                    $transformedTokens[] = '+';
                    $i += 2; // Skip the next operand as it was already processed.
                    $transformedTokens[] = $prev;
                    continue;
                }
                // Process multiplication operator: '*'
                if ($curr === '*' && is_array($prev) && is_array($next)) {
                    $transformedTokens[count($transformedTokens) - 1] = $next;
                    $transformedTokens[] = '*';
                    $i += 2;
                    $transformedTokens[] = $prev;
                    continue;
                }
            }

            // Default: append current token unchanged.
            $transformedTokens[] = $tokens[$i];
            $i++;
        }

        return $transformedTokens;
    }
}

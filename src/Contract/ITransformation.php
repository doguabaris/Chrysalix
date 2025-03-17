<?php

/**
 * ITransformation.php
 *
 * This file is part of the Chrysalix library, a PHP library for metamorphic
 * code transformations. It defines the ITransformation, which all
 * transformation rules in the library must implement.
 *
 * @category Interface
 * @package  Chrysalix
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */

namespace Chrysalix\Contract;

/**
 * ITransformation
 *
 * This interface defines the contract for all transformation rules in the Chrysalix library.
 * Each transformation must implement a method that accepts an array of PHP tokens and returns
 * a transformed array of tokens.
 *
 * @category Interface
 * @package  Chrysalix\Contract
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
interface ITransformation
{
    /**
     * Applies a transformation to an array of PHP tokens.
     *
     * This method receives an array of tokens produced by token_get_all()
     * and must return a new token array that is functionally equivalent.
     * Implementations should preserve the code semantics while modifying
     * its syntax.
     *
     * @since 0.1.0
     *
     * @param array<int, array{0: int, 1: string, 2?: int}|string> $tokens An array
     *        of PHP tokens.
     *
     * @return array<int, array{0: int, 1: string, 2?: int}|string> A transformed
     *         array of tokens.
     */
    public function transform(array $tokens): array;
}

<?php

/**
 * Chrysalix.php
 *
 * This file is part of the Chrysalix library, a PHP library for metamorphic
 * code transformations. It provides the Chrysalix class, which acts as
 * the core engine for applying a sequence of transformation rules to PHP
 * source code. The engine tokenizes the input, applies each transformation,
 * and then reassembles the tokens back into code.
 *
 * @category Engine
 * @package  Chrysalix\Core
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */

namespace Chrysalix\Core;

use Chrysalix\Contract\ITransformation;

/**
 * Chrysalix
 *
 * This class serves as the core engine of the Chrysalix library.
 * It tokenizes PHP source code, applies a sequence of transformation rules,
 * and then reassembles the tokens back into transformed code.
 *
 * @category Engine
 * @package  Chrysalix\Core
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class Chrysalix
{
    /**
     * @var ITransformation[] Array of transformation objects.
     */
    private array $transformations;

    /**
     * Constructor.
     *
     * This method initializes the Chrysalix engine with an array of
     * transformation rules. Each rule must implement the
     * ITransformation. The engine will apply these rules in
     * sequence to the provided PHP code.
     *
     * @since 0.1.0
     *
     * @param ITransformation[] $transformations Array of transformation
     *        objects to apply.
     */
    public function __construct(array $transformations = [])
    {
        $this->transformations = $transformations;
    }

    /**
     * Processes PHP code by applying all registered transformations.
     *
     * This method tokenizes the input PHP code using token_get_all(),
     * then iterates over each transformation rule to process the tokens.
     * Finally, it reassembles the tokens back into a PHP code string.
     *
     * @since 0.1.0
     *
     * @param string $code The original PHP code.
     *
     * @return string The transformed PHP code.
     */
    public function process(string $code): string
    {
        // Tokenize the code
        $tokens = token_get_all($code);

        // Apply each transformation sequentially
        foreach ($this->transformations as $transformation) {
            $tokens = $transformation->transform($tokens);
        }

        // Reassemble tokens back to a code string
        $transformedCode = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $transformedCode .= $token[1];
            } else {
                $transformedCode .= $token;
            }
        }

        return $transformedCode;
    }
}

<?php

/**
 * VariableRenamer.php
 *
 * This file is part of the Chrysalix library, a PHP library for metamorphic
 * code transformations. It provides the VariableRenamer class, which is
 * responsible for renaming variables consistently throughout PHP source code.
 * This transformation is useful for generating code variants with obfuscated
 * or altered variable names.
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
 * VariableRenamer
 *
 * This class renames variables throughout PHP source code to produce functionally equivalent,
 * but syntactically varied, versions of the code. It replaces each original variable with a unique
 * new name based on a configurable prefix.
 *
 * @category Transformation
 * @package  Chrysalix\Transformation
 * @since    0.1.0
 * @author   Doğu Abaris <abaris@null.net>
 * @license  MIT
 */
class VariableRenamer implements ITransformation
{
    /**
     * @var string Prefix to use for new variable names.
     */
    private string $prefix;

    /**
     * @var array<string, string> Mapping of original variable names to new names.
     */
    private array $variableMap = [];

    /**
     * @var int Counter for generating unique variable names.
     */
    private int $counter = 1;

    /**
     * Constructor.
     *
     * Initializes a new VariableRenamer instance with given options. The
     * 'prefix' option sets the prefix for new variable names. If not provided,
     * the default prefix 'var' is used.
     *
     * @since 0.1.0
     *
     * @param array<string, mixed> $options Optional configuration options.
     *        - 'prefix': Prefix for new variable names (default 'var').
     */
    public function __construct(array $options = [])
    {
        $this->prefix = ( isset($options['prefix']) && is_string($options['prefix']) ) ? $options['prefix'] : 'var';
    }

    /**
     * Transforms tokens by renaming all variables.
     *
     * This method iterates over the token array and replaces tokens that
     * represent variables with new names. Each original variable is mapped to
     * a unique name based on the configured prefix. The transformed token
     * array is then returned.
     *
     * @since 0.1.0
     *
     * @param array<int, array{0: int, 1: string, 2?: int}|string> $tokens An
     *        array of PHP tokens.
     *
     * @return array<int, array{0: int, 1: string, 2?: int}|string> The
     *         transformed tokens.
     */
    public function transform(array $tokens): array
    {
        $transformedTokens = [];

        foreach ($tokens as $token) {
            if (is_array($token)) {
                list( $tokenType, $tokenContent ) = $token;
                if ($tokenType === T_VARIABLE) {
                    if (!isset($this->variableMap[$tokenContent])) {
                        $newName = '$' . $this->prefix . $this->counter++;
                        $this->variableMap[$tokenContent] = $newName;
                    }
                    $token[1] = $this->variableMap[$tokenContent];
                }
            }
            $transformedTokens[] = $token;
        }

        return $transformedTokens;
    }
}

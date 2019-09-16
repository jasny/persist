<?php

declare(strict_types=1);

namespace Jasny\DB\FieldMap;

/**
 * Map MongoDB field to PHP field or visa versa.
 */
interface FieldMapInterface extends \ArrayAccess
{
    /**
     * Allow properties that are not mapped?
     */
    public function isDynamic(): bool;

    /**
     * Get the inverse of the map
     *
     * @return static
     */
    public function flip();

    /**
     * Apply mapping.
     *
     * @template T of iterable|object
     * @param T $subject
     * @return T
     */
    public function __invoke($subject);
}

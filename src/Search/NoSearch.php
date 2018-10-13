<?php

declare(strict_types=1);

namespace Jasny\DB\Search;

use Jasny\DB\CRUD\Result;
use Jasny\DB\Exception\UnsupportedFeatureException;

/**
 * Search is not supported
 */
class NoSearch implements SearchInterface
{
    /**
     * Full text search.
     *
     * @param mixed  $storage
     * @param string $terms
     * @param array  $filter
     * @param array  $opts
     * @return Result
     */
    public function search($storage, string $terms, array $filter = [], array $opts = []): Result
    {
        throw new UnsupportedFeatureException("Full text search is not supported");
    }
}

<?php

declare(strict_types=1);

namespace Jasny\DB;

use Jasny\DB\Option\OptionInterface;
use Jasny\DB\Result\Result;
use Psr\Log\LoggerInterface;

/**
 * Service for full text search.
 */
interface ReadInterface
{
    /**
     * Get underlying storage object.
     * This is DB implementation dependent.
     *
     * @return mixed
     */
    public function getStorage();

    /**
     * Enable (debug) logging.
     *
     * @return static
     */
    public function withLogging(LoggerInterface $logger): self;

    /**
     * Query and fetch data.
     *
     * @param array<string, mixed> $filter
     * @param OptionInterface[]    $opts
     * @return Result
     */
    public function fetch(array $filter = [], array $opts = []): Result;

    /**
     * Query and count result.
     *
     * @param array<string, mixed> $filter
     * @param OptionInterface[]    $opts
     * @return int
     */
    public function count(array $filter = [], array $opts = []): int;
}
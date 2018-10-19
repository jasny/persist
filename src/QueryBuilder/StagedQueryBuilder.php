<?php

declare(strict_types=1);

namespace Jasny\DB\QueryBuilder;

use Improved\IteratorPipeline\Pipeline;

/**
 * Base class for service that can convert a filter to a databsae query.
 *
 * @immutable
 */
class StagedQueryBuilder implements StagedQueryBuilderInterface
{
    /**
     * @var array<string, callable[]>
     */
    protected $stages = [
        'prepare' => [],
        'compose' => [],
        'build' => [],
        'finalize' => []
    ];


    /**
     * Create a clone with a new step
     *
     * @param string   $stage
     * @param callable $step
     * @return static
     */
    protected function withAddedStep(string $stage, callable $step)
    {
        $clone = clone $this;
        $clone->stages[$stage][] = $step;

        return $clone;
    }

    /**
     * Create a query builder, adding a custom prepare step.
     *
     * @param callable $step
     * @return static
     */
    public function onPrepare(callable $step)
    {
        return $this->withAddedStep('prepare', $step);
    }

    /**
     * Create a query builder, adding a custom compose step.
     *
     * @param callable $step
     * @return static
     */
    public function onCompose(callable $step)
    {
        return $this->withAddedStep('compose', $step);
    }

    /**
     * Create a query builder, adding a custom build step.
     *
     * @param callable $step
     * @return static
     */
    public function onBuild(callable $step)
    {
        return $this->withAddedStep('build', $step);
    }

    /**
     * Create a query builder, adding a custom finalize step.
     *
     * @param callable $step
     * @return static
     */
    public function onFinalize(callable $step)
    {
        return $this->withAddedStep('finalize', $step);
    }


    /**
     * Create the query from a filter
     *
     * @param iterable $filter
     * @param array    $opts
     * @return mixed
     */
    public function buildQuery(iterable $filter, array $opts = [])
    {
        $data = $filter;
        $steps = array_merge(...array_values($this->stages));

        foreach ($steps as $step) {
            $data = $step($data, $opts);
        }

        return $data;
    }

    /**
     * Alias of `buildQuery()`
     *
     * @param iterable $filter
     * @param array    $opts
     * @return mixed
     */
    final public function __invoke(iterable $filter, array $opts = [])
    {
        return $this->buildQuery($filter, $opts);
    }
}
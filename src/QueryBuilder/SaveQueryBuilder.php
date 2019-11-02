<?php

declare(strict_types=1);

namespace Jasny\DB\QueryBuilder;

/**
 * Query builder for save queries.
 */
class SaveQueryBuilder extends AbstractQueryBuilder
{
    /**
     * SaveQueryBuilder constructor.
     *
     * @param callable(object,array|object,mixed,OptionInterface[])
     */
    public function __construct(callable $compose)
    {
        parent::__construct($compose);
    }

    /**
     * Get the prepare logic of the query builder.
     *
     * @return callable(iterable,OptionInterface[]):iterable
     */
    public function getPreparation(): callable
    {
        return $this->prepare;
    }

    /**
     * Set the prepare logic of the query builder.
     *
     * @param callable(iterable,OptionInterface[]):iterable $prepare
     * @return static
     */
    public function withPreparation(callable $prepare): self
    {
        return $this->withProperty('prepare', $prepare);
    }

    /**
     * Apply each item to the accumulator.
     */
    protected function applyCompose(object $accumulator, iterable $items, array $opts = []): void
    {
        foreach ($items as $index => $item) {
            ($this->compose)($accumulator, $item, $index, $opts);
        }
    }
}
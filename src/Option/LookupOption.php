<?php

declare(strict_types=1);

namespace Jasny\DB\Option;

use Improved as i;
use Jasny\DB\Filter\FilterItem;
use Jasny\DB\Schema\Relationship;
use Jasny\Immutable;

/**
 * Option to lookup related data from other collections / tables.
 */
class LookupOption implements OptionInterface
{
    use Immutable\With;

    protected string $name;
    protected ?string $target = null;
    protected string $related;

    protected bool $isCount = false;

    /** @var array<string,string>|FilterItem[] */
    protected array $filter = [];

    /** @var OptionInterface[] */
    protected array $opts = [];


    /**
     * Class constructor
     * If field maps to a single relationship, the related collection and fields don't have to be specified.
     *
     * @param string $related  Related collection name (or alias)
     */
    public function __construct(string $related)
    {
        $this->name = str_replace(':', '_', $related);
        $this->related = $related;
    }

    /**
     * Get a copy with a different field name.
     *
     * @return static
     */
    public function as(string $name): self
    {
        return $this->withProperty('name', $name);
    }

    /**
     * Specify the field that the lookup applies to.
     * Null for the main collection of the query.
     *
     * @param string|null $field
     * @return static
     */
    public function for(?string $field): self
    {
        return $this->withProperty('target', $field);
    }

    /**
     * Filter the items from the related collection.
     *
     * @param array<string,string>|FilterItem[]
     * @return static
     */
    public function having(array $filter): self
    {
        return $this->withProperty('filter', array_merge($this->filter, $filter));
    }

    /**
     * Only lookup a count of the number of items.
     *
     * @return static
     */
    public function count(): self
    {
        $this->withProperty('isCount', true);
    }

    /**
     * Specify which fields to include in the related data.
     *
     * @param string ...$fields
     * @return static
     */
    public function fields(string ...$fields): self
    {
        return $this->withPropertyItem('opts', new FieldsOption($fields));
    }

    /**
     * Specify which field to exclude from the related data.
     *
     * @param string ...$fields
     * @return static
     */
    public function omit(string ...$fields): self
    {
        return $this->withPropertyItem('opts', new FieldsOption($fields, true /* negate */));
    }

    /**
     * Specify which field to exclude from the related data.
     *
     * @param string ...$fields
     * @return static
     */
    public function sort(string ...$fields): self
    {
        return $this->withPropertyItem('opts', new SortOption($fields));
    }

    /**
     * Specify which field to exclude from hydrated data.
     *
     * @param int $limit
     * @param int $offset
     * @return static
     */
    public function limit(int $limit, int $offset = 0): self
    {
        return $this->withPropertyItem('opts', new LimitOption($limit, $offset));
    }

    /**
     * Add custom option(s).
     *
     * @param OptionInterface ...$opts
     * @return static
     */
    public function with(OptionInterface ...$opts): self
    {
        return $this->withProperty('opts', array_merge($this->opts, $opts));
    }


    /**
     * Get the field this lookup applies to.
     * Null for the main collection of the query.
     *
     * @return string|null
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * Get related collection name (or alias).
     */
    public function getRelated(): string
    {
        return $this->related;
    }

    /**
     * Get field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Should a count of the number of items be looked up?
     */
    public function isCount(): bool
    {
        return $this->isCount;
    }

    /**
     * Get filter for related items.
     *
     * @return array<string,string>|FilterItem[]
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * Get options specific to the lookup.
     *
     * @return OptionInterface[]
     */
    public function getOpts(): array
    {
        return $this->opts;
    }
}

<?php

declare(strict_types=1);

namespace Jasny\Persist\Map;

use Jasny\DotKey\DotKey;

/**
 * Simple field map.
 */
final class FieldMap implements MapInterface
{
    /** @var array<string,string|false> */
    protected array $map;
    /** @var array<string,string> */
    protected array $inverse;

    /**
     * Class constructor.
     *
     * @param array<string,string|false> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
        $this->inverse = array_flip(array_filter($map));
    }

    /**
     * @inheritDoc
     */
    public function withOpts(array $opts): MapInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function applyToField(string $field)
    {
        [$top, $rest] = explode('.', $field, 2) + [1 => null];

        if (!isset($this->map[$top])) {
            return null;
        }

        return $this->map[$top] === false || $rest === null
            ? $this->map[$top]
            : $this->map[$top] . '.' . $rest;
    }

    /**
     * @inheritDoc
     */
    public function apply($item)
    {
        return $this->applyMap($this->map, $item);
    }

    /**
     * @inheritDoc
     */
    public function applyInverse($item)
    {
        return $this->applyMap($this->inverse, $item);
    }

    /**
     * Apply mapping to item.
     * Returns `null` if there are no changes.
     *
     * @param array<string,string|false> $map
     * @param array|object               $item
     * @return array|object
     *
     * @template TItem
     * @phpstan-param array<string,string|false> $map
     * @phpstan-param TItem&(array|object)       $item
     * @phpstan-return TItem
     */
    protected function applyMap(array $map, $item)
    {
        $set = [];
        $remove = [];

        foreach ($map as $field => $newField) {
            if (!DotKey::on($item)->exists($field)) {
                continue;
            }

            if ($newField !== false) {
                $set[$newField] = DotKey::on($item)->get($field);
            }
            $remove[] = $field;
        }

        $copy = $item;
        $dotkey = new DotKey($copy, DotKey::COPY);

        foreach ($remove as $field) {
            $dotkey->remove($field);
        }
        foreach ($set as $field => $value) {
            $dotkey->put($field, $value);
        }

        return $copy;
    }
}

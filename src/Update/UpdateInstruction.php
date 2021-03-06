<?php

declare(strict_types=1);

namespace Jasny\Persist\Update;

/**
 * Operation for update query
 */
class UpdateInstruction
{
    protected string $operator;

    /** @var array<string,mixed> */
    protected array $pairs;

    /**
     * Class constructor.
     *
     * @param string              $operator  Update operator
     * @param array<string,mixed> $pairs     field/value pairs
     */
    public function __construct(string $operator, array $pairs)
    {
        $this->operator = $operator;
        $this->pairs = $pairs;
    }

    /**
     * Get the operator.
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * Get the field/value pairs.
     *
     * @return array<string,mixed>
     */
    public function getPairs(): array
    {
        return $this->pairs;
    }
}

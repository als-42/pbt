<?php

namespace Rater\Services\ValidModel;

class ErrorsContainer implements \Iterator, \ArrayAccess
{
    private int $i = 0;
    /** @var Error[] $container  */
    private array $container = [];

    public function __construct()
    {
        $this->i = 0;
    }

    public function hasErrors(): bool
    {
        return !empty($this->container);
    }

    public function getErrors(): array
    {
        return $this->container;
    }

    public function push(mixed $v): void
    {
        $this->container[] = $v;
    }
    public function current(): mixed
    {
        return $this->container[$this->i];
    }

    public function next(): void
    {
        ++ $this->i;
    }

    public function key(): mixed
    {
        return $this->i;
    }

    public function valid(): bool
    {
        return isset($this->container[$this->i]);
    }

    public function rewind(): void
    {
        $this->i = 0;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->container[$offset]);

    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }
}
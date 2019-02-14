<?php

declare(strict_types=1);

namespace App\Builder;

class ElementCollection implements \Iterator, \Countable, \ArrayAccess
{
    private $position = 0;

    protected $elements = [];

    public function add(AbstractElement $element)
    {
        $this->elements[] = $element;
    }

    public function count()
    {
        return count($this->elements);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->elements[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->elements[$this->position]);
    }

    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->elements[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }
}
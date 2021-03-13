<?php

namespace MagicTest\MagicTest\Support;

use Illuminate\Support\Collection;

class AttributeCollection extends Collection
{
    public function hasAttribute(string $name): bool
    {
        return $this->where('name', $name)->isNotEmpty();
    }

    public function getAttribute(string $name): string
    {
        return $this->where('name', $name)->first()['value'];
    }

    /**
     * The "name" attribute should always be the priority.
     *
     * @return self
     */
    public function reorderItems(): self
    {
        if ($this->hasAttribute('name')) {
            $name = $this->where('name', 'name')->first();
            $items = $this->where('name', '!=', 'name');
            $items->prepend($name);

            return $items;
        }

        return $this;
    }
}

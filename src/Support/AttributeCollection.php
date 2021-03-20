<?php

namespace MagicTest\MagicTest\Support;

use Illuminate\Support\Collection;

class AttributeCollection extends Collection
{
    protected array $attributeOrder = ['dusk', 'name'];

    public function hasAttribute(string $name): bool
    {
        return $this->where('name', $name)->isNotEmpty();
    }

    public function getAttribute(string $name): string
    {
        return $this->where('name', $name)->first()->value;
    }

    /**
     * The "dusk" and "name" attributes should always be the priority.
     *
     * @return self
     */
    public function reorderItems(): self
    {
        $newCollection = new static;

        foreach ($this->attributeOrder as $attribute) {
            if ($this->hasAttribute($attribute)) {
                $newCollection->push(
                    $this->where('name', $attribute)->first()
                );
            }
        }

        return $newCollection->merge(
            $this->whereNotIn('name', $this->attributeOrder)
        );
    }
}

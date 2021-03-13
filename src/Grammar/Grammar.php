<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MagicTest\MagicTest\Element\Attribute;
use MagicTest\MagicTest\Support\AttributeCollection;

class Grammar
{
    public AttributeCollection $attributes;

    public array $parent;

    public string $tag;

    public array $meta;

    public function __construct($attributes, $parent, $tag, $meta)
    {
        $this->attributes = (new AttributeCollection($this->parseAttributes($attributes)))->reorderItems();
        $this->parent = $parent;
        $this->tag = $tag;
        $this->meta = $meta;
    }

    public static function indent(string $string, int $times = 2): string
    {
        $indentation = '';
        foreach (range(0, $times) as $i) {
            $indentation .= self::INDENT;
        }

        return $indentation . $string;
    }

    public function build(bool $last = false)
    {
        return self::indent($this->action(), 4) . ($last ? ';' : '');
    }

    public function clean(string $string)
    {
        if (! Str::startsWith($string, "'")) {
            $string = "'" . $string;
        }

        if (! Str::endsWith($string, "'")) {
            $string .= "'";
        }
        
        return trim($string);
    }

    public function trim(string $property): string
    {
        return trim($this->{$property});
    }

    public function hasTargetType(string $type): bool
    {
        return Arr::get($this->targetMeta, 'type') === $type;
    }

    public function isLivewire(): bool
    {
        return $this->attributes->hasAttribute('wire:model');
    }

    public static function for(array $command)
    {
        $types = [
            'click' => Click::class,
            'see' => See::class,
            'fill' => Fill::class,
        ];


        return new $types[$command['action']](
            $command['attributes'],
            $command['parent'],
            $command['tag'],
            $command['meta']
        );
    }

    public function pause()
    {
        return null;
    }

    public function parseAttributes(array $attributes)
    {
        return  array_map(fn ($element) => new Attribute(...array_values($element)), $attributes);
    }
}

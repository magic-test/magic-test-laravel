<?php

namespace MagicTest\MagicTest\Grammar;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Grammar
{
    public $path;

    public $target;

    public $options;

    public $classList;

    public $tag;

    public $targetMeta;

    const INDENT = '    ';

    public function __construct($path, $target, $options, $classList, $tag, $targetMeta = null)
    {
        $this->path = $path;
        $this->target = $this->clean($target);
        $this->options = $options;
        $this->classList = $classList;
        $this->tag = $tag;
        $this->targetMeta = $targetMeta;
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

    public static function for(array $command)
    {
        $types = [
            'click' => Click::class,
            'see' => See::class,
            'fill' => Fill::class,
        ];

        return new $types[$command['action']](
            $command['path'],
            $command['target'],
            $command['options'],
            $command['classList'],
            $command['tag'],
            $command['targetMeta'] ?? null
        );
    }
}

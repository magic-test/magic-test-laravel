<?php

namespace Mateusjatenee\MagicTest\Grammar;

class Grammar
{
    public $path;

    public $target;

    public $options;

    public $classList;

    public $tag;

    const INDENT = '    ';

    public function __construct($path, $target, $options, $classList, $tag)
    {
        $this->path = $path;
        $this->target = $target;
        $this->options = $options;
        $this->classList = $classList;
        $this->tag = $tag;
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

    public static function for(array $command)
    {
        $types = [
            'click' => Click::class,
        ];

        return new $types[$command['action']](...array_values($command));
    }
}

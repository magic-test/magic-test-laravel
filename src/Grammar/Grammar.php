<?php

namespace Mateusjatenee\MagicTest\Grammar;

use Mateusjatenee\MagicTest\Grammar\See;
use Mateusjatenee\MagicTest\Grammar\Fill;

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
        $this->target = $this->clean($target);
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

    public function clean(string $string)
    {
        return trim($string);
    }

    public static function for(array $command)
    {
        $types = [
            'click' => Click::class,
            'see' => See::class,
            'fill' => Fill::class
        ];

        return new $types[$command['action']](
            $command['path'], 
            $command['target'], 
            $command['options'], 
            $command['classList'],
            $command['tag']
        );
    }
}

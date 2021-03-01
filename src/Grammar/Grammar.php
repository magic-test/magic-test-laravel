<?php

namespace Mateusjatenee\MagicTest\Grammar;

class Grammar
{
    public $action;

    public $path;

    public $target;

    public $options;

    public $classList;

    public $tag;

    public function __construct($action, $path, $target, $options, $classList, $tag)
    {
        $this->action = $action;
        $this->path = $path;
        $this->target = $target;
        $this->options = $options;
        $this->classList = $classList;
        $this->tag = $tag;
    }

    public function build(bool $last = false)
    {
        $string = '                    ';
        $string .= $this->action();
        if ($last) {
            $string .= ";";
        }

        return $string;
    }

    public static function for(array $command)
    {
        $types = [
            'click' => Click::class,
        ];

        return new $types[$command['action']](...array_values($command));
    }
}

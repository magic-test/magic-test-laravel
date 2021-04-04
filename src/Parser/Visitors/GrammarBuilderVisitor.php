<?php
namespace MagicTest\MagicTest\Parser\Visitors;

use Illuminate\Support\Collection;
use MagicTest\MagicTest\Grammar\Pause;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeVisitorAbstract;

class GrammarBuilderVisitor extends NodeVisitorAbstract
{
    public function __construct(Collection $grammar)
    {
        $this->grammar = $grammar;
    }

    public function leaveNode(Node $node): void
    {
        if ($node instanceof MethodCall && $node->name->__toString() === 'magic') {
            $this->buildNodes($node);
        }
    }

    public function buildNodes($node): void
    {
        $previousMethod = $this->getPreviousMethodInChain($node);
        $grammar = $this->grammar
                        ->map(function ($grammar) {
                            return [$grammar, $grammar->pause()];
                        })->flatten()->filter();

        
        // if the last item of a grammar is a pause, it is unnecessary because
        // right now we are in the "magic" call node, so we remove it.
        // That happens unless the second to last grammar is a Livewire field.
        // In that case we have to add an extra pause regardless.
        $secondToLastGrammar = $grammar->count() > 1 ? $grammar[$grammar->count() - 2] : null;
        if (
            $grammar->last() instanceof Pause &&
            ! $secondToLastGrammar->isLivewire()
        ) {
            $grammar->pop();
        }

        // If the previous method was a method
        // that needs a pause, we prepend it.
        if (in_array($previousMethod->name->__toString(), [
            'clickLink', 'press',
        ])) {
            $grammar->prepend(new Pause(500));
        }


        // I'll be honest and say I don't entirely understand the folllowing block of code,
        // even though I wrote it.
        // I'm still a bit confused as to why there are nested method call objects inside each var
        // (so each method call's var is another method call) instead of them being siblings.
        foreach ($grammar as $gram) {
            $previousNode = clone $node;
            $node->var = new MethodCall(
                $previousNode->var,
                $gram->nameForParser(),
                $this->buildArguments($gram->arguments())
            );
        }
    }

    public function buildArguments(array $arguments): array
    {
        return array_map(fn ($argument) => new Arg($argument), $arguments);
    }

    public function getPreviousMethodInChain(Node $node): Expr
    {
        $parentExpression = $node->getAttribute('parent')->expr;

        // now we return the first var, which *should* be the previous method.
        return $parentExpression->var;
    }
}

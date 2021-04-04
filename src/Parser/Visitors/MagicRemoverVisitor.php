<?php
namespace MagicTest\MagicTest\Parser\Visitors;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeVisitorAbstract;

class MagicRemoverVisitor extends NodeVisitorAbstract
{
    public function leaveNode(Node $node): void
    {
        if ($node instanceof MethodCall && $node->name->__toString() === 'magic') {
            $previousMethod = $this->getPreviousMethodInChain($node);
        
            // We are mutating the currenet method (magic) to have the properties
            // of the previous method. That way we get rid of it. Is this the
            // correct way? No fucking clue. But it's working. I think.
            $node->name = $previousMethod->name;
            $node->var = $previousMethod->var;
            $node->args = $previousMethod->args;
        }
    }

    public function getPreviousMethodInChain(Node $node): Expr
    {
        $parentExpression = $node->getAttribute('parent')->expr;

        // now we return the first var, which *should* be the previous method.
        return $parentExpression->var;
    }
}

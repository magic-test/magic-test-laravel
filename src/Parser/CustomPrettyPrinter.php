<?php

namespace MagicTest\MagicTest\Parser;

use MagicTest\MagicTest\Grammar\Grammar;
use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;

class CustomPrettyPrinter extends Standard
{
    public function pExpr_MethodCall(Expr\MethodCall $node)
    {
        $call = Grammar::indent('->' . $this->pObjectProperty($node->name)
             . '(' . $this->pMaybeMultiline($node->args) . ')', 1);

        return $this->pDereferenceLhs($node->var) . $this->nl . $call;
    }
}

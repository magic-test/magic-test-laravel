<?php

namespace MagicTest\MagicTest\Parser;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\NodeDumper;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\NodeConnectingVisitor;
use PhpParser\NodeVisitor\ParentConnectingVisitor;

class PhpFile
{
    public static function fromContent(string $content, string $method, $grammar)
    {
        $lexer = new \PhpParser\Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $lexer);

        $ast = collect($parser->parse($content)[0]);

        $stmts = $ast['stmts'];

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());


        $oldTokens = $lexer->getTokens();


        $newStmts = $traverser->traverse($stmts);

        $nodeDumper = new NodeDumper;
        // dd($nodeDumper->dump($stmts));



        $nodeFinder = new NodeFinder;
        $class = $nodeFinder->findFirst($newStmts, function(Node $node) use ($method) {
            return $node instanceof ClassMethod &&
                   $node->name->__toString() === $method;
        });
        // dd($nodeDumper->dump($class->stmts));

        $methodCall = $nodeFinder->findFirst($class->stmts, function($node) {
            return $node instanceof MethodCall;
            if ($node instanceof MethodCall) {
                var_dump($node);
            }
            // return $node instanceof Identifier &&
                //    $node->name === 'magic';
        });

        $closure = $nodeFinder->findFirst($methodCall->args, function($node) {
            return $node->value instanceof Closure;
        })->value;


        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor(new GrammarBuilderVisitor($grammar));
        // $traverser->addVisitor(new PauseAdderVisitor);

        // add grammar
        $traverser->traverse($closure->stmts);
    

        $magicMethod = $nodeFinder->findFirst($closure->stmts, function (\PhpParser\Node $node) {
            return $node instanceof \PhpParser\Node\Expr\MethodCall && $node->name == "magic";
        });



        // dd($closure->stmts[0]->expr);







        // $traverser->traverse($callbackParse);


        // dd($closure->stmts);
        //Expression::class

$prettyPrinter = new CustomPrettyPrinter;
$newCode = $prettyPrinter->printFormatPreserving($newStmts, $stmts, $oldTokens);
    return $newCode;

    }
}

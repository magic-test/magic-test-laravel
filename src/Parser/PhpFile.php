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
use MagicTest\MagicTest\Parser\MagicRemoverVisitor;

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
        $class = $nodeFinder->findFirst($newStmts, fn(Node $node) => 
          $node instanceof ClassMethod && $node->name->__toString() === $method
        );
        $methodCall = $nodeFinder->findFirst($class->stmts, fn(Node $node) => $node instanceof MethodCall);
        $closure = $nodeFinder->findFirst($methodCall->args, fn(Node $node) => $node->value instanceof Closure)->value;



        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor(new GrammarBuilderVisitor($grammar));

        // add grammar
        $traverser->traverse($closure->stmts);
    

        $prettyPrinter = new CustomPrettyPrinter;
        $newCode = $prettyPrinter->printFormatPreserving($newStmts, $stmts, $oldTokens);

        return $newCode;
    }

    public static function finish(string $content, string $method)
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



        $nodeFinder = new NodeFinder;

        $class = $nodeFinder->findFirst($newStmts, fn(Node $node) => 
          $node instanceof ClassMethod && $node->name->__toString() === $method
        );
        $methodCall = $nodeFinder->findFirst($class->stmts, fn(Node $node) => $node instanceof MethodCall);
        $closure = $nodeFinder->findFirst($methodCall->args, fn(Node $node) => $node->value instanceof Closure)->value;


        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor(new MagicRemoverVisitor);

        // remove finish
        $traverser->traverse($closure->stmts);
    
        $prettyPrinter = new CustomPrettyPrinter;
        $newCode = $prettyPrinter->printFormatPreserving($newStmts, $stmts, $oldTokens);

        return $newCode;
    }
}

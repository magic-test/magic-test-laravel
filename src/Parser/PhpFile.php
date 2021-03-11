<?php

namespace MagicTest\MagicTest\Parser;

use Illuminate\Support\Collection;
use MagicTest\MagicTest\Parser\Printer\PrettyPrinter;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;

class PhpFile
{
    protected Parser $parser;

    protected Lexer $lexer;

    protected array $ast;

    protected array $initialStatements;

    protected array $newStatements;

    protected ?Closure $closure;

    public function __construct(string $content, string $method)
    {
        $this->lexer = new \PhpParser\Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $this->lexer);
        $this->ast = (array) $this->parser->parse($content)[0];
        $this->initialStatements = $this->ast['stmts'];
        $this->newStatements = $this->getNewStatements();
        $this->closure = $this->getClosure($method);
    }

    public static function fromContent(string $content, string $method)
    {
        return new static($content, $method);
    }

    public function addMethods(Collection $grammar): string
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor(new GrammarBuilderVisitor($grammar));

        // add grammar
        $traverser->traverse($this->closure->stmts);


        return (new PrettyPrinter)->printFormatPreserving(
            $this->newStatements,
            $this->initialStatements,
            $this->lexer->getTokens()
        );
    }

    public function finish(): string
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor(new MagicRemoverVisitor);


        $traverser->traverse($this->closure->stmts);


        return (new PrettyPrinter)->printFormatPreserving(
            $this->newStatements,
            $this->initialStatements,
            $this->lexer->getTokens()
        );
    }

    /**
     * Clone the statements to leave the starting ones untouched so they can be diffed by the printer later.
     *
     * @return array
     */
    protected function getNewStatements(): array
    {
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new CloningVisitor);

        return $traverser->traverse($this->initialStatements);
    }

    protected function getClassMethod(string $method): ?ClassMethod
    {
        return (new NodeFinder)->findFirst(
            $this->newStatements,
            fn (Node $node) => $node instanceof ClassMethod && $node->name->__toString() === $method
        );
    }

    protected function getMethodCall(ClassMethod $classMethod): ?MethodCall
    {
        return (new NodeFinder)->findFirst($classMethod->stmts, fn (Node $node) => $node instanceof MethodCall);
    }

    protected function getClosure(string $method): ?Closure
    {
        $classMethod = $this->getClassMethod($method);
        $methodCall = $this->getMethodCall($classMethod);

        return (new NodeFinder)->findFirst($methodCall->args, fn (Node $node) => $node->value instanceof Closure)->value;
    }
}

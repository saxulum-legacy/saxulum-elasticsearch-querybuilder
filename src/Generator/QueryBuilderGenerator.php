<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Generator;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\PrettyPrinter\Standard as PhpGenerator;

final class QueryBuilderGenerator
{
    /**
     * @var PhpGenerator
     */
    private $phpGenerator;

    /**
     * @param PhpGenerator $phpGenerator
     */
    public function __construct(PhpGenerator $phpGenerator)
    {
        $this->phpGenerator = $phpGenerator;
    }

    /**
     * @param $query
     * @return string
     */
    public function generateByJson($query): string
    {
        $data = json_decode($query, false);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(sprintf('Message: %s, query: %s', json_last_error_msg(), $query));
        }

        $queryBuilder = new Variable('queryBuilder');

        $stmts = [];

        $stmts[] = $this->createQueryBuilderNode();
        $stmts[] = $this->appendChildrenToObjectNode($queryBuilder, $queryBuilder, $data);

        return $this->structureCode($this->phpGenerator->prettyPrint($stmts));
    }

    /**
     * @param string $code
     * @return string
     */
    private function structureCode(string $code): string
    {
        $codeWithLinebreaks = str_replace('->add', "\n->add", $code);
        $codeWithLinebreaks = str_replace('->end', "\n->end", $codeWithLinebreaks);

        $lines = explode("\n", $codeWithLinebreaks);

        $position = 0;

        $structuredLines = [];

        foreach ($lines as $i => $line) {
            $lastLine = $lines[$i-1] ?? '';
            if (0 === strpos($line, '->add')) {
                if (false === strpos($lastLine, '->end') && false === strpos($lastLine, '->scalarNode')) {
                    $position++;
                }
                $structuredLines[] = str_pad('', $position * 4) . $line;
            } elseif (0 === strpos($line, '->end')) {
                if (strpos($lastLine, '->objectNode') || strpos($lastLine, '->arrayNode')) {
                    $structuredLines[count($structuredLines) - 1] .= '->end()';
                } else {
                    $position--;
                    $structuredLines[] = str_pad('', $position * 4) . $line;
                }
            } else {
                $structuredLines[] = $line;
            }
        }

        return implode("\n", $structuredLines);
    }

    /**
     * @return Expr
     */
    private function createQueryBuilderNode(): Expr
    {
        return new Assign(new Variable('queryBuilder'), new New_(new Name('QueryBuilder')));
    }

    /**
     * @param Expr $expr
     * @return Expr
     */
    private function createObjectNode(Expr $expr): Expr
    {
        return new MethodCall($expr, 'objectNode');
    }

    /**
     * @param Expr $expr
     * @return Expr
     */
    private function createArrayNode(Expr $expr): Expr
    {
        return new MethodCall($expr, 'arrayNode');
    }

    /**
     * @param Expr $expr
     * @param string|float|int|bool|null $value
     * @return Expr
     */
    private function createScalarNode(Expr $expr, $value): Expr
    {
        if (is_string($value)) {
            $valueExpr = new String_($value);
        } elseif (is_int($value)) {
            $valueExpr = new LNumber($value);
        } elseif (is_float($value)) {
            $valueExpr = new DNumber($value);
        } elseif (is_bool($value)) {
            $valueExpr = new ConstFetch(new Name($value ? 'true' : 'false'));
        } else {
            $valueExpr = new ConstFetch(new Name('null'));
        }

        return new MethodCall($expr, 'scalarNode', [new Arg($valueExpr)]);
    }

    /**
     * @param Expr $queryBuilder
     * @param Expr $expr
     * @param array $data
     * @return Expr
     */
    private function appendChildrenToArrayNode(Expr $queryBuilder, Expr $expr, array $data)
    {
        foreach ($data as $value) {
            if ($value instanceof \stdClass) {
                $argument = $this->createObjectNode($queryBuilder);
            } elseif (is_array($value)) {
                $argument = $this->createArrayNode($queryBuilder);
            } else {
                $argument = $this->createScalarNode($queryBuilder, $value);
            }

            $expr = new MethodCall($expr, 'addToArrayNode', [new Arg($argument)]);

            if ($value instanceof \stdClass) {
                $expr = new MethodCall($this->appendChildrenToObjectNode($queryBuilder, $expr, $value), 'end');
            } elseif (is_array($value)) {
                $expr = new MethodCall($this->appendChildrenToArrayNode($queryBuilder, $expr, $value), 'end');
            }
        }

        return $expr;
    }

    /**
     * @param Expr $queryBuilder
     * @param Expr $expr
     * @param \stdClass $data
     * @return Expr
     */
    private function appendChildrenToObjectNode(Expr $queryBuilder, Expr $expr, \stdClass $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof \stdClass) {
                $argument = $this->createObjectNode($queryBuilder);
            } elseif (is_array($value)) {
                $argument = $this->createArrayNode($queryBuilder);
            } else {
                $argument = $this->createScalarNode($queryBuilder, $value);
            }

            $expr = new MethodCall($expr, 'addToObjectNode', [new Arg(new String_($key)), new Arg($argument)]);

            if ($value instanceof \stdClass) {
                $expr = new MethodCall($this->appendChildrenToObjectNode($queryBuilder, $expr, $value), 'end');
            } elseif (is_array($value)) {
                $expr = new MethodCall($this->appendChildrenToArrayNode($queryBuilder, $expr, $value), 'end');
            }
        }

        return $expr;
    }
}

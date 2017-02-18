<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

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
use PhpParser\PrettyPrinter\Standard;

final class Generator
{
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

        $stmts = [$this->createQueryBuilderNode()];

        $queryBuilder = new Variable('queryBuilder');

        if ($data instanceof \stdClass) {
            $stmts[] = $this->appendChildrenToObjectNode($queryBuilder, $queryBuilder, $data);
        } else {
            $stmts[] = $this->appendChildrenToArrayNode($queryBuilder, $queryBuilder, $data);
        }

        $prettyPrinter = new Standard();

        $code = $prettyPrinter->prettyPrint($stmts);

        return $code;
    }

    /**
     * @return Expr
     */
    private function createQueryBuilderNode(): Expr
    {
        return new Assign(new Variable('queryBuilder'), new New_(new Name('\\' . QueryBuilder::class)));
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

        // todo: append value based on type...
        return new MethodCall($expr, 'scalarNode', [new Arg($valueExpr)]);
    }

    /**
     * @param Expr $expr
     * @param \stdClass|array $data
     * @return Expr
     */
    private function appendChildren(Expr $queryBuilder, Expr $expr, $data): Expr
    {
        if ($data instanceof \stdClass) {
            return $this->appendChildrenToObjectNode($queryBuilder, $expr, $data);
        }

        return $this->appendChildrenToArrayNode($queryBuilder, $expr, $data);
    }

    /**
     * @param Expr $queryBuilder
     * @param Expr $expr
     * @param array $data
     * @return Expr
     */
    private function appendChildrenToArrayNode(Expr $queryBuilder, Expr $expr, array $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof \stdClass) {
                $expr = new MethodCall($expr, 'addToArrayNode', [new Arg($this->createObjectNode($queryBuilder))]);
                $expr = $this->appendChildrenToObjectNode($queryBuilder, $expr, $value);
                $expr = new MethodCall($expr, 'end');
            } elseif (is_array($value)) {
                $expr = new MethodCall($expr, 'addToArrayNode', [new Arg($this->createArrayNode($queryBuilder))]);
                $expr = $this->appendChildrenToArrayNode($queryBuilder, $expr, $value);
                $expr = new MethodCall($expr, 'end');
            } else {
                $expr = new MethodCall($expr, 'addToArrayNode', [new Arg($this->createScalarNode($queryBuilder, $value))]);
            }
        }
        return $expr;
    }

    /**
     * @param Expr $expr
     * @param \stdClass $data
     * @return Expr
     */
    private function appendChildrenToObjectNode(Expr $queryBuilder, Expr $expr, \stdClass $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof \stdClass) {
                $expr = new MethodCall($expr, 'addToObjectNode', [new Arg(new String_($key)), new Arg($this->createObjectNode($queryBuilder))]);
                $expr = $this->appendChildrenToObjectNode($queryBuilder, $expr, $value);
                $expr = new MethodCall($expr, 'end');
            } elseif (is_array($value)) {
                $expr = new MethodCall($expr, 'addToObjectNode', [new Arg(new String_($key)), new Arg($this->createArrayNode($queryBuilder))]);
                $expr = $this->appendChildrenToArrayNode($queryBuilder, $expr, $value);
                $expr = new MethodCall($expr, 'end');
            } else {
                $expr = new MethodCall($expr, 'addToObjectNode', [new Arg(new String_($key)), new Arg($this->createScalarNode($queryBuilder, $value))]);
            }
        }

        return $expr;
    }
}

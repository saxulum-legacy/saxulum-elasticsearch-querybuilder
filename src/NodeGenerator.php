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
use PhpParser\PrettyPrinter\Standard as PhpGenerator;

final class NodeGenerator
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

        if ($data instanceof \stdClass) {
            $expr = $this->appendChildrenToObjectNode($data);
        } else {
            $expr = $this->appendChildrenToArrayNode($data);
        }

        $code = $this->phpGenerator->prettyPrint([new Assign(new Variable('node'), $expr)]);

        return $this->structureCode($code);
    }

    /**
     * @param string $code
     * @return string
     */
    private function structureCode(string $code): string
    {
        $codeWithLinebreaks = str_replace('->add', "\n->add", substr($code, 0, -1));

        $lines = explode("\n", $codeWithLinebreaks);

        $position = 0;

        $structuredLines = [];

        foreach ($lines as $i => $line) {
            $lastStructuredLine = $structuredLines[count($structuredLines) - 1] ?? '';
            if (0 === strpos($line, '->add') &&
                false === strpos($lastStructuredLine, ' )') &&
                false === strpos($lastStructuredLine, 'ScalarNode')) {
                $position++;
            }
            $lineLength = strlen($line);
            $braceCount = 0;
            while (')' === $line[--$lineLength]) {
                $braceCount++;
            }
            $prefix = str_pad('', $position * 4);
            if ($braceCount > 2) {
                $structuredLines[] = $prefix . substr($line, 0, ($braceCount - 2 * -1));
            } else {
                $structuredLines[] = $prefix . $line;
            }

            while ($braceCount-- > 2) {
                $position--;
                $structuredLines[] = str_pad('', $position * 4) . ')';
            }
        }

        $structuredLines[count($structuredLines) - 1] .= ';';

        return implode("\n", $structuredLines);
    }

    /**
     * @return Expr
     */
    private function createObjectNode(): Expr
    {
        return new New_(new Name('ObjectNode'));
    }

    /**
     * @return Expr
     */
    private function createArrayNode(): Expr
    {
        return new New_(new Name('ArrayNode'));
    }

    /**
     * @param string|float|int|bool|null $value
     * @return Expr
     */
    private function createScalarNode($value): Expr
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

        return new New_(new Name('ScalarNode'), [new Arg($valueExpr)]);
    }

    /**
     * @param array $data
     * @return Expr
     */
    private function appendChildrenToArrayNode(array $data)
    {
        $expr = $this->createArrayNode();

        foreach ($data as $key => $value) {
            if ($value instanceof \stdClass) {
                $nodeExpr = $this->createObjectNode();
            } elseif (is_array($value)) {
                $nodeExpr = $this->createArrayNode();
            } else {
                $nodeExpr = $this->createScalarNode($value);
            }

            if ($value instanceof \stdClass) {
                $nodeExpr = $this->appendChildrenToObjectNode($value);
            } elseif (is_array($value)) {
                $nodeExpr = $this->appendChildrenToArrayNode($value);
            }

            $expr = new MethodCall($expr, 'add', [new Arg($nodeExpr)]);
        }

        return $expr;
    }

    /**
     * @param \stdClass $data
     * @return Expr
     */
    private function appendChildrenToObjectNode(\stdClass $data)
    {
        $expr = $this->createObjectNode();

        foreach ($data as $key => $value) {
            if ($value instanceof \stdClass) {
                $nodeExpr = $this->createObjectNode();
            } elseif (is_array($value)) {
                $nodeExpr = $this->createArrayNode();
            } else {
                $nodeExpr = $this->createScalarNode($value);
            }

            if ($value instanceof \stdClass) {
                $nodeExpr = $this->appendChildrenToObjectNode($value);
            } elseif (is_array($value)) {
                $nodeExpr = $this->appendChildrenToArrayNode($value);
            }

            $expr = new MethodCall($expr, 'add', [new Arg(new String_($key)), new Arg($nodeExpr)]);
        }

        return $expr;
    }
}

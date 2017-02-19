<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use PhpParser\Error;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard as PhpGenerator;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

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

//        $code = '<?php
//        $node = (new ObjectNode())
//            ->add(\'query\', (new ObjectNode())
//                ->add(\'bool\', (new ObjectNode())
//                    ->add(\'must\', (new ObjectNode())
//                        ->add(\'term\', (new ObjectNode())
//                            ->add(\'user\', (new ScalarNode(\'kimchy\')))
//                        )
//                    )
//                    ->add(\'filter\', (new ObjectNode())
//                        ->add(\'term\', (new ObjectNode())
//                            ->add(\'tag\', (new ScalarNode(\'tech\')))
//                        )
//                    )
//                )
//            );
//        ';
//
//        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
//
//        try {
//            $stmts = $parser->parse($code);
//            print_r($stmts); die;
//            // $stmts is an array of statement nodes
//        } catch (Error $e) {
//            echo 'Parse Error: ', $e->getMessage();
//        }
//
//        die;

















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
        $codeWithLinebreaks = str_replace('->add', "\n->add", $code);

        $lines = explode("\n", $codeWithLinebreaks);

        $position = 0;

        $structuredLines = [];

        foreach ($lines as $i => $line) {
            if (0 === strpos($line, '->add') && false === strpos($structuredLines[count($structuredLines) - 1], ' )')) {
                $position++;
            }
            $lineLength = count($lines) - 1 !== $i ? strlen($line) -1 : strlen($line) - 2;
            $braceCount = 0;
            while(')' === $line[$lineLength--]) {
                $braceCount++;
            }
            $prefix = str_pad('', $position * 4);
            if ($braceCount > 2) {
                $structuredLines[] = $prefix . substr($line, 0, - ($braceCount - 2));
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

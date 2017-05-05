<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNodeSerializeInterface;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

interface QueryBuilderInterface extends ObjectNodeSerializeInterface
{
    /**
     * @param array ...$arguments
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function add(...$arguments): QueryBuilderInterface;

    /**
     * @param AbstractNode $node
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node): QueryBuilderInterface;

    /**
     * @param string       $key
     * @param AbstractNode $node
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node): QueryBuilderInterface;

    /**
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function end(): QueryBuilderInterface;

    /**
     * @param bool $allowSerializeEmpty
     *
     * @return ArrayNode
     */
    public function arrayNode(bool $allowSerializeEmpty = false): ArrayNode;

    /**
     * @param bool|null $value
     * @param bool      $allowSerializeEmpty
     *
     * @return BoolNode
     */
    public function boolNode($value = null, bool $allowSerializeEmpty = false): BoolNode;

    /**
     * @param float|null $value
     * @param bool       $allowSerializeEmpty
     *
     * @return FloatNode
     */
    public function floatNode($value = null, bool $allowSerializeEmpty = false): FloatNode;

    /**
     * @param int|null $value
     * @param bool     $allowSerializeEmpty
     *
     * @return IntNode
     */
    public function intNode($value = null, bool $allowSerializeEmpty = false): IntNode;

    /**
     * @return NullNode
     */
    public function nullNode(): NullNode;

    /**
     * @param bool $allowSerializeEmpty
     *
     * @return ObjectNode
     */
    public function objectNode(bool $allowSerializeEmpty = false): ObjectNode;

    /**
     * @param string|null $value
     * @param bool        $allowSerializeEmpty
     *
     * @return StringNode
     */
    public function stringNode($value = null, bool $allowSerializeEmpty = false): StringNode;
}

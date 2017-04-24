<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNodeSerializeInterface;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

interface QueryBuilderInterface extends ObjectNodeSerializeInterface
{
    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node, bool $allowDefault = false): QueryBuilderInterface;

    /**
     * @param string       $key
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node, bool $allowDefault = false): QueryBuilderInterface;

    /**
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function end(): QueryBuilderInterface;

    /**
     * @return ArrayNode
     */
    public function arrayNode(): ArrayNode;

    /**
     * @param bool|null
     * @return BoolNode
     */
    public function boolNode($value = null): BoolNode;

    /**
     * @param float|null
     * @return FloatNode
     */
    public function floatNode($value = null): FloatNode;

    /**
     * @param int|null
     * @return IntNode
     */
    public function intNode($value = null): IntNode;

    /**
     * @return ObjectNode
     */
    public function objectNode(): ObjectNode;

    /**
     * @deprecated use boolNode|floatNode|intNode|stringNode
     * @param string|float|int|bool|null $value
     * @return ScalarNode
     */
    public function scalarNode($value = null): ScalarNode;

    /**
     * @param string|null
     * @return StringNode
     */
    public function stringNode($value = null): StringNode;

    /**
     * @param boolean $beautify
     * @return string
     */
    public function json(bool $beautify = false): string;
}

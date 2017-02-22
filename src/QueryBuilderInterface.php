<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

interface QueryBuilderInterface
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
     */
    public function end(): QueryBuilderInterface;

    /**
     * @return ArrayNode
     */
    public function arrayNode(): ArrayNode;

    /**
     * @return ObjectNode
     */
    public function objectNode(): ObjectNode;

    /**
     * @param string|float|int|bool|null $value
     * @return ScalarNode
     */
    public function scalarNode($value = null): ScalarNode;

    /**
     * @return \stdClass|null
     */
    public function serialize();

    /**
     * @param boolean $beautify
     * @return string
     */
    public function json(bool $beautify = false): string;
}

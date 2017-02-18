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
     * @return self
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node, bool $allowDefault = false): self;

    /**
     * @param string       $key
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return self
     *
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node, bool $allowDefault = false): self;

    /**
     * @return self
     */
    public function end(): self;

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

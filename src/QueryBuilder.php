<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

final class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var ObjectNode
     */
    private $rootNode;

    /**
     * @var AbstractNode
     */
    private $node;

    public function __construct()
    {
        $this->rootNode = new ObjectNode();
        $this->node = $this->rootNode;
    }

    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node, bool $allowDefault = false): QueryBuilderInterface
    {
        if (!$this->node instanceof ArrayNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($node, $allowDefault);
        $this->reassignParent($node);

        return $this;
    }

    /**
     * @param string       $key
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node, bool $allowDefault = false): QueryBuilderInterface
    {
        if (!$this->node instanceof ObjectNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($key, $node, $allowDefault);
        $this->reassignParent($node);

        return $this;
    }

    /**
     * @param AbstractNode $node
     */
    private function reassignParent(AbstractNode $node)
    {
        if ($node instanceof ArrayNode || $node instanceof ObjectNode) {
            $this->node = $node;
        }
    }

    /**
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function end(): QueryBuilderInterface
    {
        if (null === $this->node = $this->node->getParent()) {
            throw new \Exception(sprintf('You cannot call %s on main node', __FUNCTION__));
        }

        return $this;
    }

    /**
     * @return ArrayNode
     */
    public function arrayNode(): ArrayNode
    {
        return new ArrayNode();
    }

    /**
     * @return ObjectNode
     */
    public function objectNode(): ObjectNode
    {
        return new ObjectNode();
    }

    /**
     * @param string|float|int|bool|null $value
     * @return ScalarNode
     */
    public function scalarNode($value = null): ScalarNode
    {
        return new ScalarNode($value);
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        return $this->rootNode->serialize();
    }

    /**
     * @param boolean $beautify
     * @return string
     */
    public function json(bool $beautify = false): string
    {
        if (null === $serialized = $this->serialize()) {
            return '';
        }

        if ($beautify) {
            return json_encode($serialized, JSON_PRETTY_PRINT);
        }

        return json_encode($serialized);
    }
}

<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class QueryBuilder
{
    /**
     * @var ObjectNode
     */
    protected $rootNode;

    /**
     * @var AbstractNode
     */
    protected $node;

    public function __construct()
    {
        $this->rootNode = new ObjectNode();
        $this->node = $this->rootNode;
    }

    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node, $allowDefault = false)
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
     * @return $this
     *
     * @throws \Exception
     */
    public function addToObjectNode($key, AbstractNode $node, $allowDefault = false)
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
    protected function reassignParent(AbstractNode $node)
    {
        if ($node instanceof ArrayNode || $node instanceof ObjectNode) {
            $this->node = $node;
        }
    }

    /**
     * @return $this
     */
    public function end()
    {
        $this->node = $this->node->getParent();

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
     * @param null $value
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
    public function json($beautify = false)
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

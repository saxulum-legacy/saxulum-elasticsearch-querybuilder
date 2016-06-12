<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

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
     * @param bool $allowDefault
     * @return $this
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
     * @param string $key
     * @param AbstractNode $node
     * @param bool $allowDefault
     * @return $this
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
     * @return Query
     */
    public function query()
    {
        return new Query($this->rootNode);
    }
}

<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class NodeChildRelation
{
    /**
     * @var AbstractNode
     */
    protected $node;

    /**
     * @var bool
     */
    protected $allowDefault;

    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     */
    public function __construct(AbstractNode $node, $allowDefault)
    {
        $this->node = $node;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return bool
     */
    public function isAllowDefault()
    {
        return $this->allowDefault;
    }
}

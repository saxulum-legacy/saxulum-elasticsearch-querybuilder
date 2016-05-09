<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;

class Expr
{
    /**
     * @var AbstractNode
     */
    protected $node;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var bool
     */
    protected $allowDefault;

    /**
     * @param AbstractNode $node
     */
    public function __construct(AbstractNode $node)
    {
        $this->node = $node;
        $this->allowDefault = false;
    }

    /**
     * @return AbstractNode
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function key($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return $this
     */
    public function allowDefault()
    {
        $this->allowDefault = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowDefault()
    {
        return $this->allowDefault;
    }
}

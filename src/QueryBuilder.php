<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;
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
     * @return Expr
     */
    public function a()
    {
        return new Expr(new ArrayNode());
    }

    /**
     * @param \Closure $callback
     *
     * @return Expr
     */
    public function c(\Closure $callback)
    {
        return new Expr(new CallbackNode($callback));
    }

    /**
     * @return Expr
     */
    public function o()
    {
        return new Expr(new ObjectNode());
    }

    /**
     * @param string|float|int|bool|null $value
     *
     * @return Expr
     */
    public function s($value = null)
    {
        return new Expr(new ScalarNode($value));
    }

    /**
     * @param Expr $expr
     *
     * @return $this
     */
    public function add(Expr $expr)
    {
        $this->addChild($expr);
        $this->reassignParent($expr);

        return $this;
    }

    /**
     * @param Expr $expr
     */
    protected function addChild(Expr $expr)
    {
        $node = $expr->getNode();
        if ($this->node instanceof ArrayNode) {
            $this->node->add($node, $expr->isAllowDefault());
        } elseif ($this->node instanceof ObjectNode) {
            $this->node->add($expr->getKey(), $node, $expr->isAllowDefault());
        }
    }

    /**
     * @param Expr $expr
     */
    protected function reassignParent(Expr $expr)
    {
        $node = $expr->getNode();
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

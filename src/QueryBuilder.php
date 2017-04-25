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
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

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
     * @param array ...$arguments
     * @return QueryBuilderInterface
     * @throws \Exception
     */
    public function add(...$arguments): QueryBuilderInterface
    {
        if ($this->node instanceof ObjectNode) {
            return $this->addToObjectNode(...$arguments);
        }

        return $this->addToArrayNode(...$arguments);
    }

    /**
     * @param AbstractNode $node
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToArrayNode(AbstractNode $node): QueryBuilderInterface
    {
        if (!$this->node instanceof ArrayNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($node);
        $this->reassignParent($node);

        return $this;
    }

    /**
     * @param string       $key
     * @param AbstractNode $node
     *
     * @return QueryBuilderInterface
     *
     * @throws \Exception
     */
    public function addToObjectNode(string $key, AbstractNode $node): QueryBuilderInterface
    {
        if (!$this->node instanceof ObjectNode) {
            throw new \Exception(sprintf('You cannot call %s on node type: %s', __FUNCTION__, get_class($this->node)));
        }

        $this->node->add($key, $node);
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
     * @param bool $allowSerializeEmpty
     * @return ArrayNode
     */
    public function arrayNode(bool $allowSerializeEmpty = false): ArrayNode
    {
        return new ArrayNode($allowSerializeEmpty);
    }

    /**
     * @param bool|null $value
     * @param bool $allowSerializeEmpty
     * @return BoolNode
     */
    public function boolNode($value = null, bool $allowSerializeEmpty = false): BoolNode
    {
        return new BoolNode($value, $allowSerializeEmpty);
    }

    /**
     * @param float|null $value
     * @param bool $allowSerializeEmpty
     * @return FloatNode
     */
    public function floatNode($value = null, bool $allowSerializeEmpty = false): FloatNode
    {
        return new FloatNode($value, $allowSerializeEmpty);
    }

    /**
     * @param int|null $value
     * @param bool $allowSerializeEmpty
     * @return IntNode
     */
    public function intNode($value = null, bool $allowSerializeEmpty = false): IntNode
    {
        return new IntNode($value, $allowSerializeEmpty);
    }

    /**
     * @return NullNode
     */
    public function nullNode(): NullNode
    {
        return new NullNode;
    }

    /**
     * @param bool $allowSerializeEmpty
     * @return ObjectNode
     */
    public function objectNode(bool $allowSerializeEmpty = false): ObjectNode
    {
        return new ObjectNode($allowSerializeEmpty);
    }

    /**
     * @param string|null $value
     * @param bool $allowSerializeEmpty
     * @return StringNode
     */
    public function stringNode($value = null, bool $allowSerializeEmpty = false): StringNode
    {
        return new StringNode($value, $allowSerializeEmpty);
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

<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ArrayNode implements NodeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var NodeInterface[]|array
     */
    protected $children = [];

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param NodeInterface $node
     * @return $this
     */
    public function add(NodeInterface $node)
    {
        if (false !== $index = array_search($node, $this->children, true)) {
            throw new \InvalidArgumentException(sprintf('Child already added index: %d', $index));
        }

        $this->children[] = $node;

        return $this;
    }

    /**
     * @param NodeInterface $node
     * @return $this
     */
    public function remove(NodeInterface $node)
    {
        if (false !== $index = array_search($node, $this->children, true)) {
            unset($this->children[$index]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $serialzedChildren = [];
        foreach ($this->children as $child) {
            $serialzedChildren[] = $child->serialize();
        }

        $serialized = new \stdClass();
        $serialized->{$this->getName()} = $serialzedChildren;

        return $serialized;
    }
}

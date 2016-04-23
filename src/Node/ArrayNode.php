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
    public function addChild(NodeInterface $node)
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
    public function removeChild(NodeInterface $node)
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
        $value = [];
        foreach ($this->children as $child) {
            $value[] = $child->serialize();
        }

        return [$this->getName() => $value];
    }
}

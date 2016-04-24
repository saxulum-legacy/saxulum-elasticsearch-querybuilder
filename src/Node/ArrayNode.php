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
     * @var boolean
     */
    protected $allowNoChildren;

    /**
     * @param string $name
     * @param boolean $allowNoChildren
     */
    public function __construct($name, $allowNoChildren = false)
    {
        $this->name = $name;
        $this->allowNoChildren = $allowNoChildren;
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
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialzedChildren = [];
        $serialzedChildrenCount = 0;
        foreach ($this->children as $child) {
            if (null !== $serialzedChild = $child->serialize()) {
                $serialzedChildren[] = $serialzedChild;
                $serialzedChildrenCount++;
            }
        }

        if (0 === $serialzedChildrenCount && !$this->allowNoChildren) {
            return null;
        }

        $serialized = new \stdClass();
        $serialized->{$this->getName()} = $serialzedChildren;

        return $serialized;
    }
}

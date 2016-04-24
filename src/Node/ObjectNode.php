<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ObjectNode implements NodeInterface
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
        if (isset($this->children[$node->getName()])) {
            throw new \InvalidArgumentException(sprintf('There is already a child with name: %s', $node->getName()));
        }

        $this->children[$node->getName()] = $node;

        return $this;
    }

    /**
     * @param NodeInterface $node
     * @return $this
     */
    public function remove(NodeInterface $node)
    {
        if (isset($this->children[$node->getName()])) {
            unset($this->children[$node->getName()]);
        }

        return $this;
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialzedChildren = new \stdClass();
        $serialzedChildrenCount = 0;
        foreach ($this->children as $child) {
            if (null !== $serialzedChild = $child->serialize()) {
                $serialzedChildren->{$child->getName()} = $serialzedChild->{$child->getName()};
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

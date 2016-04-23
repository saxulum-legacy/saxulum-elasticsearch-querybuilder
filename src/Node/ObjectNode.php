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
     * @return \stdClass
     */
    public function serialize()
    {
        $serialzedChildren = new \stdClass();
        foreach ($this->children as $child) {
            $serialzedChildren->{$child->getName()} = $child->serialize()->{$child->getName()};
        }
        
        $serialized = new \stdClass();
        $serialized->{$this->getName()} = $serialzedChildren;
        
        return $serialized;
    }
}

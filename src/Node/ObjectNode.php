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
    public function addChild(NodeInterface $node)
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
    public function removeChild(NodeInterface $node)
    {
        if (isset($this->children[$node->getName()])) {
            unset($this->children[$node->getName()]);
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
            $value[$child->getName()] = $child->serialize()[$child->getName()];
        }
        
        return [$this->getName() => $value];
    }
}

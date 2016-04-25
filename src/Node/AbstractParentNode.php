<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /**
     * @var AbstractNode[]|array
     */
    protected $children = [];

    /**
     * @var boolean
     */
    protected $allowNull;

    /**
     * @var \ReflectionProperty
     */
    protected $reflectionProperty;

    /**
     * @param boolean $allowNull
     */
    public function __construct($allowNull = false)
    {
        $this->allowNull = $allowNull;

        $this->reflectionProperty = new \ReflectionProperty(AbstractNode::classname, 'parent');
        $this->reflectionProperty->setAccessible(true);
    }

    /**
     * @param AbstractNode $node
     * @return void
     */
    protected function assignParent(AbstractNode $node)
    {
        if (null !== $this->reflectionProperty->getValue($node)) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->reflectionProperty->setValue($node, $this);
    }
}

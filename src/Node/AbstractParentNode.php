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
    protected $allowEmpty;

    /**
     * @var \ReflectionProperty
     */
    protected $reflectionProperty;

    /**
     * @param boolean $allowEmpty
     */
    public function __construct($allowEmpty = false)
    {
        $this->allowEmpty = $allowEmpty;

        $this->reflectionProperty = new \ReflectionProperty(AbstractNode::classname, 'parent');
        $this->reflectionProperty->setAccessible(true);
    }

    /**
     * @param AbstractNode $node
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function assignParent(AbstractNode $node)
    {
        if (null !== $this->reflectionProperty->getValue($node)) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->reflectionProperty->setValue($node, $this);
    }
}

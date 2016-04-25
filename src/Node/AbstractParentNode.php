<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /**
     * @var AbstractNode[]|array
     */
    protected $children = [];

    /**
     * @var \ReflectionProperty
     */
    protected $parentReflection;

    /**
     * @var \ReflectionProperty
     */
    protected $allowDefaultReflection;

    /**
     * @param boolean $allowDefault
     */
    public function __construct($allowDefault = false)
    {
        $this->allowDefault = $allowDefault;

        $this->parentReflection = new \ReflectionProperty(AbstractNode::class, 'parent');
        $this->parentReflection->setAccessible(true);

        $this->allowDefaultReflection = new \ReflectionProperty(AbstractNode::class, 'allowDefault');
        $this->allowDefaultReflection->setAccessible(true);
    }

    /**
     * @param AbstractNode $node
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function assignParent(AbstractNode $node)
    {
        if (null !== $this->parentReflection->getValue($node)) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->parentReflection->setValue($node, $this);
    }
}

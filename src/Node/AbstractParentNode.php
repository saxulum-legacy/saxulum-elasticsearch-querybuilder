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
        
        $this->reflectionProperty = new \ReflectionProperty(AbstractNode::class, 'parent');
        $this->reflectionProperty->setAccessible(true);
    }
}

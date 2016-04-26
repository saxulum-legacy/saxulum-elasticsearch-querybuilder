<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /**
     * @var AbstractNode[]|array
     */
    protected $children = [];

    /**
     * @param boolean $allowDefault
     */
    public function __construct($allowDefault = false)
    {
        $this->allowDefault = $allowDefault;
    }
}

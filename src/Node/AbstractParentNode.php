<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /**
     * @var AbstractNode[]|array
     */
    protected $children = [];

    /**
     * @param bool $allowAddDefault
     */
    public function __construct($allowAddDefault = false)
    {
        $this->allowAddDefault = $allowAddDefault;
    }
}

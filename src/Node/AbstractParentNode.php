<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractParentNode extends AbstractNode
{
    /**
     * @var AbstractNode[]|array
     */
    protected $children = [];

    /**
     * @var bool[]|array
     */
    protected $allowDefault = [];

    /**
     * @param bool $allowAddDefault
     */
    public function __construct($allowAddDefault = false)
    {
        $this->allowAddDefault = $allowAddDefault;
    }
}

<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractNode
{
    /**
     * @var string
     */
    const classname = __CLASS__;

    /**
     * @var AbstractParentNode
     */
    protected $parent;

    /**
     * @var boolean
     */
    protected $allowDefault;

    /**
     * @return \stdClass|array|string|float|integer|boolean|null
     */
    abstract public function serialize();
}

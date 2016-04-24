<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractNode
{
    /**
     * @var AbstractParentNode
     */
    protected $parent;

    /**
     * @return \stdClass|array|string|float|integer|boolean|null
     */
    abstract public function serialize();
}

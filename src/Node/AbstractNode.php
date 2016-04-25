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
    protected $allowNull;

    /**
     * @return boolean
     */
    public function allowNull()
    {
        return $this->allowNull;
    }

    /**
     * @return \stdClass|array|string|float|integer|boolean|null
     */
    abstract public function serialize();
}

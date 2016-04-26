<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractNode
{
    /**
     * @var AbstractParentNode
     */
    protected $parent;

    /**
     * @param AbstractParentNode $parent
     */
    public function setParent(AbstractParentNode $parent)
    {
        if (null !== $this->parent) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->parent = $parent;
    }

    /**
     * @var boolean
     */
    protected $allowDefault;

    /**
     * @return boolean
     */
    public function allowDefault()
    {
        return $this->allowDefault;
    }

    /**
     * @return \stdClass|array|string|float|integer|boolean|null
     */
    abstract public function serialize();
}

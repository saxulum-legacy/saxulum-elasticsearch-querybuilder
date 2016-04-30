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
     *
     * @throws \InvalidArgumentException
     */
    public function setParent(AbstractParentNode $parent)
    {
        if (null !== $this->parent) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->parent = $parent;
    }

    /**
     * @return AbstractParentNode
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return \stdClass|array|null
     */
    abstract public function getDefault();

    /**
     * @return \stdClass|array|string|float|int|bool|null
     */
    abstract public function serialize();
}

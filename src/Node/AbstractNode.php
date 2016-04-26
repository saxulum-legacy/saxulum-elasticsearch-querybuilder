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
     * @var bool
     */
    protected $allowAddDefault;

    /**
     * @return bool
     */
    public function allowAddDefault()
    {
        return $this->allowAddDefault;
    }

    /**
     * @return \stdClass|array|null
     */
    abstract public function getAddDefault();

    /**
     * @return \stdClass|array|string|float|int|bool|null
     */
    abstract public function serialize();
}

<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

abstract class AbstractNode
{
    /**
     * @var AbstractParentNode
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $allowSerializeEmpty;

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
     * @return AbstractParentNode|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    public function isAllowSerializeEmpty(): bool
    {
        return $this->allowSerializeEmpty;
    }

    /**
     * @return \stdClass|array|null
     */
    abstract public function serializeEmpty();

    /**
     * @return \stdClass|array|string|float|int|bool|null
     */
    abstract public function serialize();
}

<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ObjectNode extends AbstractParentNode implements ObjectNodeSerializeInterface
{
    /**
     * @param bool $allowDefault
     */
    public function __construct(bool $allowDefault = false)
    {
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param string       $key
     * @param AbstractNode $node
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function add($key, AbstractNode $node)
    {
        if (isset($this->children[$key])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with key %s!', $key));
        }

        $node->setParent($this);

        $this->children[$key] = $node;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getDefault(): \stdClass
    {
        return new \stdClass();
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = new \stdClass();
        foreach ($this->children as $key => $child) {
            $this->serializeChild($serialized, $key, $child);
        }

        if ([] === (array) $serialized) {
            return;
        }

        return $serialized;
    }

    /**
     * @param \stdClass         $serialized
     * @param string            $key
     * @param AbstractNode $child
     */
    private function serializeChild(\stdClass $serialized, string $key, AbstractNode $child)
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized->$key = $serializedChild;
        } elseif ($child->isAllowDefault()) {
            $serialized->$key = $child->getDefault();
        }
    }
}

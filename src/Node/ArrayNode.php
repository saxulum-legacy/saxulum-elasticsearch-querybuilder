<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ArrayNode extends AbstractParentNode
{
    /**
     * @param bool $allowDefault
     */
    public function __construct(bool $allowDefault = false)
    {
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param AbstractNode $node
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function add(AbstractNode $node)
    {
        $node->setParent($this);

        $this->children[] = $node;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefault(): array
    {
        return [];
    }

    /**
     * @return array|null
     */
    public function serialize()
    {
        $serialized = [];
        foreach ($this->children as $child) {
            $this->serializeChild($serialized, $child);
        }

        if ([] === $serialized) {
            return;
        }

        return $serialized;
    }

    /**
     * @param array             $serialized
     * @param AbstractNode $child
     */
    private function serializeChild(array &$serialized, AbstractNode $child)
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized[] = $serializedChild;
        } elseif ($child->isAllowDefault()) {
            $serialized[] = $child->getDefault();
        }
    }
}

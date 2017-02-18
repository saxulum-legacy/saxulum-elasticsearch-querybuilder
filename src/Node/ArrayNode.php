<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ArrayNode extends AbstractParentNode
{
    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function add(AbstractNode $node, $allowDefault = false)
    {
        $node->setParent($this);

        $this->children[] = new NodeChildRelation($node, $allowDefault);

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
     * @param NodeChildRelation $child
     */
    private function serializeChild(array &$serialized, NodeChildRelation $child)
    {
        if (null !== $serializedChild = $child->getNode()->serialize()) {
            $serialized[] = $serializedChild;
        } elseif ($child->isAllowDefault()) {
            $serialized[] = $child->getNode()->getDefault();
        }
    }
}

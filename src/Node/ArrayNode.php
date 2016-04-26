<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ArrayNode extends AbstractParentNode
{
    /**
     * @param AbstractNode $node
     *
     * @return $this
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
    public function getAddDefault()
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
            if (null !== $serializedChild = $child->serialize()) {
                $serialized[] = $serializedChild;
            } elseif ($child->allowAddDefault()) {
                $serialized[] = $child->getAddDefault();
            }
        }

        if ([] === $serialized) {
            return;
        }

        return $serialized;
    }
}

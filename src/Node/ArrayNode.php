<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ArrayNode extends AbstractParentNode
{
    /**
     * @param AbstractNode $node
     * @return $this
     */
    public function add(AbstractNode $node)
    {
        $node->setParent($this);

        $this->children[] = $node;

        return $this;
    }

    /**
     * @return array|null
     */
    public function serialize()
    {
        $serialized = [];
        foreach ($this->children as $child) {
            $serializedChild = $child->serialize();
            if (null !== $serializedChild || $child->allowDefault()) {
                $serialized[] = $serializedChild;
            }
        }

        if (!$this->allowDefault && [] === $serialized) {
            return null;
        }

        return $serialized;
    }
}

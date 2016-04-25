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
        $this->assignParent($node);

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
            if (null !== $serializedChild || $this->allowDefaultReflection->getValue($child)) {
                $serialized[] = $serializedChild;
            }
        }

        if (!$this->allowDefault && [] === $serialized) {
            return null;
        }

        return $serialized;
    }
}

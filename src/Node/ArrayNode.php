<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ArrayNode extends AbstractParentNode
{
    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return $this
     */
    public function add(AbstractNode $node, $allowDefault = false)
    {
        $node->setParent($this);

        $this->children[] = $node;
        $this->allowDefault[] = $allowDefault;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefault()
    {
        return [];
    }

    /**
     * @return array|null
     */
    public function serialize()
    {
        $serialized = [];
        foreach ($this->children as $i => $child) {
            if (null !== $serializedChild = $child->serialize()) {
                $serialized[] = $serializedChild;
            } elseif ($this->allowDefault[$i]) {
                $serialized[] = $child->getDefault();
            }
        }

        if ([] === $serialized) {
            return;
        }

        return $serialized;
    }
}

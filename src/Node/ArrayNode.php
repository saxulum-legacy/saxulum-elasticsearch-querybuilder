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
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = [];
        foreach ($this->children as $i => $child) {
            $this->serializeChild($serialized, $i, $child);
        }

        if ([] === $serialized) {
            return;
        }

        return $serialized;
    }

    /**
     * @param array        $serialized
     * @param string       $name
     * @param AbstractNode $child
     */
    private function serializeChild(array &$serialized, $i, AbstractNode $child)
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized[] = $serializedChild;
        } elseif ($this->allowDefault[$i]) {
            $serialized[] = $child->getDefault();
        }
    }
}

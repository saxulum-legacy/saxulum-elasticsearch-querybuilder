<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ObjectNode extends AbstractParentNode
{
    /**
     * @param string $name
     * @param AbstractNode $node
     * @return $this
     */
    public function add($name, AbstractNode $node)
    {
        if (isset($this->children[$name])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with name %s!', $name));
        }

        $node->setParent($this);

        $this->children[$name] = $node;

        return $this;
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = new \stdClass();
        foreach ($this->children as $name => $child) {
            $serializedChild = $child->serialize();
            if (null !== $serializedChild || $child->allowDefault()) {
                $serialized->$name = $serializedChild;
            }
        }

        if (!$this->allowDefault && [] === (array) $serialized) {
            return null;
        }

        return $serialized;
    }
}

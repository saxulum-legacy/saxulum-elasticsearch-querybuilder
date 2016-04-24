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
        $this->assignParent($node);

        if (isset($this->children[$name])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with name %s!', $name));
        }

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
            if (null !== $serializedChild || $child->allowNull()) {
                $serialized->$name = $serializedChild;
            }
        }

        if (!$this->allowNull && [] === (array) $serialized) {
            return null;
        }

        return $serialized;
    }
}

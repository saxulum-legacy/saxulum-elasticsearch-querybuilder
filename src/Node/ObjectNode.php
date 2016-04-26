<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ObjectNode extends AbstractParentNode
{
    /**
     * @param string       $name
     * @param AbstractNode $node
     *
     * @return $this
     */
    public function add($name, AbstractNode $node, $allowDefault = false)
    {
        if (isset($this->children[$name])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with name %s!', $name));
        }

        $node->setParent($this);

        $this->children[$name] = $node;
        $this->allowDefault[$name] = $allowDefault;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefault()
    {
        return new \stdClass();
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = new \stdClass();
        foreach ($this->children as $name => $child) {
            if (null !== $serializedChild = $child->serialize()) {
                $serialized->$name = $serializedChild;
            } elseif ($this->allowDefault[$name]) {
                $serialized->$name = $child->getDefault();
            }
        }

        if ([] === (array) $serialized) {
            return;
        }

        return $serialized;
    }
}

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
        if (null !== $this->reflectionProperty->getValue($node)) {
            throw new \InvalidArgumentException('Node already got a parent!');
        }

        $this->setParent($node);
        
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
            if (null !== $serializedChild = $child->serialize()) {
                $serialized->$name = $serializedChild;
            }
        }

        if (!$this->allowEmpty && [] === (array) $serialized) {
            return null;
        }

        return $serialized;
    }
}

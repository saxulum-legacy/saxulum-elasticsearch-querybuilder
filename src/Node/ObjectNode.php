<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ObjectNode extends AbstractParentNode
{
    /**
     * @var array
     */
    protected $emptyNameMapping = [];

    /**
     * @param string $name
     * @param AbstractNode $node
     * @param string|null $emptyName
     * @return $this
     */
    public function add($name, AbstractNode $node, $emptyName = null)
    {
        $this->assignParent($node);

        if (isset($this->children[$name])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with name %s!', $name));
        }

        $this->children[$name] = $node;

        if (null !== $emptyName) {
            $this->emptyNameMapping[$name] = $emptyName;
        }

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
                if ([] === (array) $serializedChild && isset($this->emptyNameMapping[$name])) {
                    $serialized->{$this->emptyNameMapping[$name]} = $serializedChild;
                } else {
                    $serialized->$name = $serializedChild;
                }
            }
        }

        if (!$this->allowEmpty && [] === (array) $serialized) {
            return null;
        }

        return $serialized;
    }
}

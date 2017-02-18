<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ObjectNode extends AbstractParentNode
{
    /**
     * @param string       $key
     * @param AbstractNode $node
     * @param bool         $allowDefault
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function add($key, AbstractNode $node, $allowDefault = false)
    {
        if (isset($this->children[$key])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with key %s!', $key));
        }

        $node->setParent($this);

        $this->children[$key] = new NodeChildRelation($node, $allowDefault);

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getDefault(): \stdClass
    {
        return new \stdClass();
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        $serialized = new \stdClass();
        foreach ($this->children as $key => $child) {
            $this->serializeChild($serialized, $key, $child);
        }

        if ([] === (array) $serialized) {
            return;
        }

        return $serialized;
    }

    /**
     * @param \stdClass         $serialized
     * @param string            $key
     * @param NodeChildRelation $child
     */
    private function serializeChild(\stdClass $serialized, string $key, NodeChildRelation $child)
    {
        if (null !== $serializedChild = $child->getNode()->serialize()) {
            $serialized->$key = $serializedChild;
        } elseif ($child->isAllowDefault()) {
            $serialized->$key = $child->getNode()->getDefault();
        }
    }
}

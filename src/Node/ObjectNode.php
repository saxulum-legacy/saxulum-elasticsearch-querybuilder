<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ObjectNode extends AbstractParentNode implements ObjectNodeSerializeInterface
{
    /**
     * @param bool $allowSerializeEmpty
     * @return ObjectNode
     */
    public static function create(bool $allowSerializeEmpty = false): ObjectNode
    {
        $node = new self;
        $node->allowSerializeEmpty = $allowSerializeEmpty;

        return $node;
    }

    /**
     * @param string       $key
     * @param AbstractNode $node
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function add($key, AbstractNode $node)
    {
        if (isset($this->children[$key])) {
            throw new \InvalidArgumentException(sprintf('There is already a node with key %s!', $key));
        }

        $node->setParent($this);

        $this->children[$key] = $node;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function serializeEmpty(): \stdClass
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
     * @param AbstractNode $child
     */
    private function serializeChild(\stdClass $serialized, string $key, AbstractNode $child)
    {
        if (null !== $serializedChild = $child->serialize()) {
            $serialized->$key = $serializedChild;
        } elseif ($child->isAllowSerializeEmpty()) {
            $serialized->$key = $child->serializeEmpty();
        }
    }

    /**
     * @param boolean $beautify
     * @return string
     */
    public function json(bool $beautify = false): string
    {
        if (null === $serialized = $this->serialize()) {
            return '';
        }

        if ($beautify) {
            return json_encode($serialized, JSON_PRETTY_PRINT);
        }

        return json_encode($serialized);
    }
}

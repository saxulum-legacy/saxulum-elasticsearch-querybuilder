<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

class Query
{
    /**
     * @var ObjectNode
     */
    protected $node;

    /**
     * @param ObjectNode $node
     */
    public function __construct(ObjectNode $node)
    {
        $this->node = $node;
    }

    /**
     * @return null|\stdClass
     */
    public function serialize()
    {
        return $this->node->serialize();
    }

    /**
     * @param bool $beautify
     *
     * @return string
     */
    public function json($beautify = false)
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

<?php

namespace Saxulum\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;

class Query
{
    /**
     * @var AbstractNode
     */
    protected $node;

    /**
     * @param AbstractNode $node
     */
    public function __construct(AbstractNode $node)
    {
        $this->node = $node;
    }

    /**
     * @return array|bool|float|int|null|\stdClass|string
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
        $serialized = $this->serialize();

        if ($beautify) {
            return json_encode($serialized, JSON_PRETTY_PRINT);
        }

        return json_encode($serialized);
    }
}

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
     * @return string
     */
    public function json()
    {
        return json_encode($this->serialize());
    }
}

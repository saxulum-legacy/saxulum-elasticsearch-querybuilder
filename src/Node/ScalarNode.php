<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ScalarNode extends AbstractNode
{
    /**
     * @var string|float|integer|boolean|null
     */
    protected $value;

    /**
     * @param string|float|integer|boolean|null $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string|float|integer|boolean|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

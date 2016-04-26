<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ScalarNode extends AbstractNode
{
    /**
     * @var string|float|int|bool|null
     */
    protected $value;

    /**
     * @param string|float|int|bool|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function getDefault()
    {
        return;
    }

    /**
     * @return string|float|int|bool|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

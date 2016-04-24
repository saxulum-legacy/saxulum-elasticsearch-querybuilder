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
     * @param boolean $allowNull
     */
    public function __construct($value, $allowNull = false)
    {
        $this->value = $value;
        $this->allowNull = $allowNull;
    }

    /**
     * @return string|float|integer|boolean|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

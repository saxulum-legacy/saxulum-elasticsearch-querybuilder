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
     * @param boolean $allowDefault
     */
    public function __construct($value, $allowDefault = false)
    {
        $this->value = $value;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return string|float|integer|boolean|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

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
     * @param boolean $allowEmpty
     */
    public function __construct($value, $allowEmpty = false)
    {
        $this->value = $value;
        $this->allowEmpty = $allowEmpty;
    }

    /**
     * @return string|float|integer|boolean|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

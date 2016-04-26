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
     * @param bool                       $allowDefault
     */
    public function __construct($value, $allowDefault = false)
    {
        $this->value = $value;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return string|float|int|bool|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

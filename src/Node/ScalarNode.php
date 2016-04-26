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
     * @param bool                       $allowAddDefault
     */
    public function __construct($value, $allowAddDefault = false)
    {
        $this->value = $value;
        $this->allowAddDefault = $allowAddDefault;
    }

    /**
     * @return null
     */
    public function getAddDefault()
    {
        return null;
    }

    /**
     * @return string|float|int|bool|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

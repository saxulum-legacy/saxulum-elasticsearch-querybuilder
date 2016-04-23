<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class ScalarNode implements NodeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|float|integer|null
     */
    protected $value;

    /**
     * @param string $name
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return array
     */
    public function serialize()
    {
        return [$this->getName() => $this->value];
    }
}

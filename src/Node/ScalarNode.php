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
     * @var boolean
     */
    protected $allowNull;

    /**
     * @param string $name
     * @param string|float|integer|null $value
     * @param boolean $allowNull
     */
    public function __construct($name, $value, $allowNull = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->allowNull = $allowNull;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \stdClass|null
     */
    public function serialize()
    {
        if (null === $this->value && !$this->allowNull) {
            return null;
        }

        $serialized = new \stdClass();
        $serialized->{$this->getName()} = $this->value;

        return $serialized;
    }
}

<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class ScalarNode extends AbstractNode
{
    /**
     * @var string|float|int|bool|null
     */
    private $value;

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

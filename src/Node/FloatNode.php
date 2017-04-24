<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class FloatNode extends AbstractNode
{
    /**
     * @var float|null
     */
    private $value;

    /**
     * @param float|null $value
     */
    public function __construct(float $value = null)
    {
        $this->value = $value;
    }

    public function getDefault()
    {
        return;
    }

    /**
     * @return float|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

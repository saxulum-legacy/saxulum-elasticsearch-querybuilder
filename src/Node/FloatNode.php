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
     * @param bool $allowDefault
     */
    public function __construct(float $value = null, bool $allowDefault = false)
    {
        $this->value = $value;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return null
     */
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

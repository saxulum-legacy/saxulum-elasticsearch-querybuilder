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
     * @param bool $allowSerializeEmpty
     */
    public function __construct(float $value = null, bool $allowSerializeEmpty = false)
    {
        $this->value = $value;
        $this->allowSerializeEmpty = $allowSerializeEmpty;
    }

    /**
     * @return null
     */
    public function serializeEmpty()
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

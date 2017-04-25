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
     * @return FloatNode
     */
    public static function create(float $value = null, bool $allowSerializeEmpty = false): FloatNode
    {
        $node = new self;
        $node->value = $value;
        $node->allowSerializeEmpty = $allowSerializeEmpty;

        return $node;
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

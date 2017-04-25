<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class BoolNode extends AbstractNode
{
    /**
     * @var bool|null
     */
    private $value;

    /**
     * @param bool|null $value
     * @param bool $allowSerializeEmpty
     * @return BoolNode
     */
    public static function create(bool $value = null, bool $allowSerializeEmpty = false): BoolNode
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
     * @return bool|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class IntNode extends AbstractNode
{
    /**
     * @var int|null
     */
    private $value;

    /**
     * @param int|null $value
     * @param bool     $allowSerializeEmpty
     *
     * @return IntNode
     */
    public static function create(int $value = null, bool $allowSerializeEmpty = false): IntNode
    {
        $node = new self();
        $node->value = $value;
        $node->allowSerializeEmpty = $allowSerializeEmpty;

        return $node;
    }

    public function serializeEmpty()
    {
        return;
    }

    /**
     * @return int|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

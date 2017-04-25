<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class StringNode extends AbstractNode
{
    /**
     * @var string|null
     */
    private $value;

    /**
     * @param string|null $value
     * @param bool $allowSerializeEmpty
     * @return StringNode
     */
    public static function create(string $value = null, bool $allowSerializeEmpty = false): StringNode
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
     * @return string|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

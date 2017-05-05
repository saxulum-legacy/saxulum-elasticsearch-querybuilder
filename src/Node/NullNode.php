<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class NullNode extends AbstractNode
{
    /**
     * @return NullNode
     */
    public static function create(): NullNode
    {
        $node = new self();
        $node->allowSerializeEmpty = true;

        return $node;
    }

    public function serializeEmpty()
    {
        return;
    }

    public function serialize()
    {
        return;
    }
}

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
        $node = new self;
        $node->allowSerializeEmpty = true;

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
     * @return null
     */
    public function serialize()
    {
        return;
    }
}

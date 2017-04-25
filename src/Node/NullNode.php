<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class NullNode extends AbstractNode
{
    public function __construct()
    {
        $this->allowDefault = true;
    }

    /**
     * @return null
     */
    public function getDefault()
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

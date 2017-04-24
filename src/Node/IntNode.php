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
     */
    public function __construct(int $value = null)
    {
        $this->value = $value;
    }

    public function getDefault()
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

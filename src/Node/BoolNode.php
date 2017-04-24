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
     */
    public function __construct(bool $value = null)
    {
        $this->value = $value;
    }

    public function getDefault()
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

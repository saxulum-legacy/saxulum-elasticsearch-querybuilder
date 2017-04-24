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
     */
    public function __construct(string $value = null)
    {
        $this->value = $value;
    }

    public function getDefault()
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

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
     * @param bool $allowDefault
     */
    public function __construct(int $value = null, bool $allowDefault = false)
    {
        $this->value = $value;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return null
     */
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

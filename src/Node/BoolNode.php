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
     * @param bool
     */
    public function __construct(bool $value = null, bool $allowDefault = false)
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
     * @return bool|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

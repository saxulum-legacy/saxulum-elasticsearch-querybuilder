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
     * @param bool $allowSerializeEmpty
     */
    public function __construct(int $value = null, bool $allowSerializeEmpty = false)
    {
        $this->value = $value;
        $this->allowSerializeEmpty = $allowSerializeEmpty;
    }

    /**
     * @return null
     */
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

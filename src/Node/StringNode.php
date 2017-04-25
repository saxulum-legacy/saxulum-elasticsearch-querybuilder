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
     */
    public function __construct(string $value = null, bool $allowSerializeEmpty = false)
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
     * @return string|null
     */
    public function serialize()
    {
        return $this->value;
    }
}

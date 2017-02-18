<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

final class NodeChildRelation
{
    /**
     * @var AbstractNode
     */
    private $node;

    /**
     * @var bool
     */
    private $allowDefault;

    /**
     * @param AbstractNode $node
     * @param bool         $allowDefault
     */
    public function __construct(AbstractNode $node, bool $allowDefault)
    {
        $this->node = $node;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return AbstractNode
     */
    public function getNode(): AbstractNode
    {
        return $this->node;
    }

    /**
     * @return bool
     */
    public function isAllowDefault(): bool
    {
        return $this->allowDefault;
    }
}

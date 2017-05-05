<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode;

interface IteratableToNodeConverterInterface
{
    /**
     * @param array|\Traversable $params
     *
     * @return AbstractParentNode
     *
     * @throws \InvalidArgumentException
     */
    public function convert($params): AbstractParentNode;
}

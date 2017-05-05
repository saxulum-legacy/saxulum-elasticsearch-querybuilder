<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;

interface ScalarToNodeConverterInterface
{
    /**
     * @param bool|float|int|null|string $value
     * @param string                     $path
     *
     * @return AbstractNode
     *
     * @throws \InvalidArgumentException
     */
    public function convert($value, string $path = ''): AbstractNode;
}

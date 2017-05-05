<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

final class ScalarToNodeConverter implements ScalarToNodeConverterInterface
{
    /**
     * @param bool|float|integer|null|string $value
     * @param string                         $path
     * @return AbstractNode
     * @throws \InvalidArgumentException
     */
    public function convert($value, string $path = ''): AbstractNode
    {
        $type = gettype($value);

        if ($type === 'boolean') {
            return BoolNode::create($value);
        }

        if ($type === 'double') {
            return FloatNode::create($value);
        }

        if ($type === 'integer') {
            return IntNode::create($value);
        }

        if ($type === 'NULL') {
            return NullNode::create($value);
        }

        if ($type === 'string') {
            return StringNode::create($value);
        }

        throw new \InvalidArgumentException(
            sprintf('Type %s is not supported, at path %s', is_object($value) ? get_class($value) : $type, $path)
        );
    }
}

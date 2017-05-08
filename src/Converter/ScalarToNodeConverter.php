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
     * @var array
     */
    private $typeMapping = [
        'boolean' => BoolNode::class,
        'double' => FloatNode::class,
        'integer' => IntNode::class,
        'string' => StringNode::class,
    ];

    /**
     * @param bool|float|int|null|string $value
     * @param string                     $path
     *
     * @return AbstractNode
     *
     * @throws \InvalidArgumentException
     */
    public function convert($value, string $path = ''): AbstractNode
    {
        if (null === $value) {
            return NullNode::create();
        }

        $type = gettype($value);

        if (!isset($this->typeMapping[$type])) {
            throw new \InvalidArgumentException(
                sprintf('Type %s is not supported, at path %s', is_object($value) ? get_class($value) : $type, $path)
            );
        }

        $node = $this->typeMapping[$type];

        return $node::create($value);
    }
}

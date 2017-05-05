<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode;
use Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

final class IteratableToNodeConverter implements IteratableToNodeConverterInterface
{
    /**
     * @var ScalarToNodeConverterInterface
     */
    private $scalarToNodeConverter;

    /**
     * @param ScalarToNodeConverterInterface $scalarToNodeConverter
     */
    public function __construct(ScalarToNodeConverterInterface $scalarToNodeConverter)
    {
        $this->scalarToNodeConverter = $scalarToNodeConverter;
    }

    /**
     * @param array|\Traversable $data
     * @param string             $path
     * @return AbstractParentNode
     * @throws \InvalidArgumentException
     */
    public function convert($data, string $path = ''): AbstractParentNode
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf('Params need to be array or %s', \Traversable::class));
        }
        
        $isArray = $this->isArray($data);
        
        $dataNode = $isArray ? ArrayNode::create() : ObjectNode::create();
        foreach ($data as $key => $value) {
            $node = $this->getNode($value, $this->getSubPath($path, $key, $isArray));

            if ($isArray) {
                $dataNode->add($node);
            } else {
                $dataNode->add((string) $key, $node);
            }
        }

        return $dataNode;
    }

    /**
     * @param array|\Traversable $data
     * @return bool
     */
    private function isArray($data): bool
    {
        $counter = 0;
        foreach ($data as $key => $value) {
            if ($key !== $counter) {
                return false;
            }

            $counter++;
        }

        return true;
    }

    /**
     * @param string     $path
     * @param string|int $key
     * @param bool       $isArray
     * @return string
     */
    private function getSubPath(string $path, $key, bool $isArray): string
    {
        $key = (string) $key;

        if ($isArray) {
            return $path . '[' . $key . ']';
        }

        return $path !== '' ? $path . '.' . $key : $key;
    }

    /**
     * @param mixed  $value
     * @param string $path
     * @return AbstractNode
     */
    private function getNode($value, string $path): AbstractNode
    {
        if (is_array($value) || $value instanceof \Traversable) {
            return $this->convert($value, $path);
        }

        return $this->scalarToNodeConverter->convert($value, $path);
    }
}

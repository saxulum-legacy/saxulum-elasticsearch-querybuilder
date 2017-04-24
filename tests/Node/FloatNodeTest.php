<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\FloatNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class FloatNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new FloatNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new FloatNode();

        self::assertNull($node->getDefault());
    }

    public function testSerializeWithValue()
    {
        $node = new FloatNode(1.0);

        self::assertSame(1.0, $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = new FloatNode();

        self::assertNull($node->serialize());
    }
}

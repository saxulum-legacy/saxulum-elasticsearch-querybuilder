<?php

declare(strict_types=1);

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
        $node = FloatNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = FloatNode::create();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithValue()
    {
        $node = FloatNode::create(1.0);

        self::assertSame(1.0, $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = FloatNode::create();

        self::assertNull($node->serialize());
    }
}

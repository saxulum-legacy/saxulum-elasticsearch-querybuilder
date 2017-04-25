<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\IntNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class IntNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = IntNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = IntNode::create();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithValue()
    {
        $node = IntNode::create(1);

        self::assertSame(1, $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = IntNode::create();

        self::assertNull($node->serialize());
    }
}

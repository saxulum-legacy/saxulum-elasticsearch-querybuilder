<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\BoolNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class BoolNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = BoolNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = BoolNode::create();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithValue()
    {
        $node = BoolNode::create(true);

        self::assertTrue($node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = BoolNode::create();

        self::assertNull($node->serialize());
    }
}

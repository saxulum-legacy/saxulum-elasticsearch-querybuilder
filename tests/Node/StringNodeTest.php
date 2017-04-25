<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\StringNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class StringNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = StringNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = StringNode::create();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithValue()
    {
        $node = StringNode::create('value');

        self::assertSame('value', $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = StringNode::create();

        self::assertNull($node->serialize());
    }
}

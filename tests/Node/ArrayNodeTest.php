<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class ArrayNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = ArrayNode::create();

        self::assertNull($node->getParent());

        $parent = ArrayNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = ArrayNode::create();

        self::assertEquals([], $node->serializeEmpty());
    }

    public function testSerialize()
    {
        $node = ArrayNode::create();

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildren()
    {
        $node = ArrayNode::create();
        $node->add(StringNode::create('value1'));
        $node->add(StringNode::create('value2'));

        self::assertEquals(['value1', 'value2'], $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = ArrayNode::create();
        $node->add(StringNode::create());
        $node->add(StringNode::create());

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAndSerializeEmpty()
    {
        $node = ArrayNode::create();
        $node->add(StringNode::create(null, true));
        $node->add(StringNode::create(null, true));

        self::assertEquals([null, null], $node->serialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node already got a parent!
     */
    public function testAddSameNodeTwice()
    {
        $node = ArrayNode::create();

        $subNode = StringNode::create('value');

        $node->add($subNode);
        $node->add($subNode);
    }
}

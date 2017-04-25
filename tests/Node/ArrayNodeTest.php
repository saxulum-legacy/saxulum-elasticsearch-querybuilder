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
        $node = new ArrayNode();

        self::assertNull($node->getParent());

        $parent = new ArrayNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new ArrayNode();

        self::assertEquals([], $node->getDefault());
    }

    public function testSerialize()
    {
        $node = new ArrayNode();

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildren()
    {
        $node = new ArrayNode();
        $node->add(new StringNode('value1'));
        $node->add(new StringNode('value2'));

        self::assertEquals(['value1', 'value2'], $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = new ArrayNode();
        $node->add(new StringNode());
        $node->add(new StringNode());

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAndDefault()
    {
        $node = new ArrayNode();
        $node->add(new StringNode(null, true));
        $node->add(new StringNode(null, true));

        self::assertEquals([null, null], $node->serialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node already got a parent!
     */
    public function testAddSameNodeTwice()
    {
        $node = new ArrayNode();

        $subNode = new StringNode('value');

        $node->add($subNode);
        $node->add($subNode);
    }
}

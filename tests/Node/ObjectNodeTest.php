<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class ObjectNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = ObjectNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = ObjectNode::create();

        self::assertEquals(new \stdClass(), $node->serializeEmpty());
    }

    public function testSerialize()
    {
        $node = ObjectNode::create();

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildren()
    {
        $node = ObjectNode::create();
        $node->add('key1', StringNode::create('value1'));
        $node->add('key2', StringNode::create('value2'));

        $serialzed = new \stdClass();
        $serialzed->key1 = 'value1';
        $serialzed->key2 = 'value2';

        self::assertEquals($serialzed, $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = ObjectNode::create();
        $node->add('key1', StringNode::create());
        $node->add('key2', StringNode::create());

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAndSerializeEmpty()
    {
        $node = ObjectNode::create();
        $node->add('key1', StringNode::create(null, true));
        $node->add('key2', StringNode::create(null, true));

        $serialzed = new \stdClass();
        $serialzed->key1 = null;
        $serialzed->key2 = null;

        self::assertEquals($serialzed, $node->serialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node already got a parent!
     */
    public function testAddSameNodeTwice()
    {
        $node = ObjectNode::create();

        $subNode = StringNode::create('value');

        $node->add('key1', $subNode);
        $node->add('key2', $subNode);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is already a node with key key1!
     */
    public function testAddSameNameTwice()
    {
        $node = ObjectNode::create();

        $subNode1 = StringNode::create('value1');
        $subNode2 = StringNode::create('value2');

        $node->add('key1', $subNode1);
        $node->add('key1', $subNode2);
    }
}

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractParentNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class ObjectNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefault()
    {
        $node = new ObjectNode();

        self::assertEquals(new \stdClass(), $node->getDefault());
    }

    public function testSerialize()
    {
        $node = new ObjectNode();

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildren()
    {
        $node = new ObjectNode();
        $node->add('key1', new ScalarNode('value1'));
        $node->add('key2', new ScalarNode('value2'));

        $serialzed = new \stdClass();
        $serialzed->key1 = 'value1';
        $serialzed->key2 = 'value2';

        self::assertEquals($serialzed, $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = new ObjectNode();
        $node->add('key1', new ScalarNode());
        $node->add('key2', new ScalarNode());

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAndDefault()
    {
        $node = new ObjectNode();
        $node->add('key1', new ScalarNode(), true);
        $node->add('key2', new ScalarNode(), true);

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
        $node = new ObjectNode();

        $subNode = new ScalarNode('value');

        $node->add('key1', $subNode);
        $node->add('key2', $subNode);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is already a node with name key1!
     */
    public function testAddSameNameTwice()
    {
        $node = new ObjectNode();

        $subNode1 = new ScalarNode('value1');
        $subNode2 = new ScalarNode('value2');

        $node->add('key1', $subNode1);
        $node->add('key1', $subNode2);
    }
}

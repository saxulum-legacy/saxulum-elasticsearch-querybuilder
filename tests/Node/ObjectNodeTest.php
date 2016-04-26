<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ObjectNodeTest extends \PHPUnit_Framework_TestCase
{
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
        $node->add('key1', new ScalarNode(null));
        $node->add('key2', new ScalarNode(null));

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAllowDefault()
    {
        $node = new ObjectNode();
        $node->add('key1', new ScalarNode(null));
        $node->add('key2', new ScalarNode(null));

        self::assertEquals(null, $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAllowChildDefault()
    {
        $node = new ObjectNode();
        $node->add('key1', new ScalarNode(null), true);
        $node->add('key2', new ScalarNode(null), true);

        $serialzed = new \stdClass();
        $serialzed->key1 = null;
        $serialzed->key2 = null;

        self::assertEquals($serialzed, $node->serialize());
    }

    public function testSerializeWithCallbackChildrenWithNullValueAllowDefault()
    {
        $node = new ObjectNode();
        $node->add('key1', new CallbackNode(function () {}));
        $node->add('key2', new CallbackNode(function () {}));

        self::assertEquals(null, $node->serialize());
    }

    public function testSerializeWithCallbackChildrenWithNullValueAllowChildDefault()
    {
        $node = new ObjectNode();
        $node->add('key1', new CallbackNode(function () {}), true);
        $node->add('key2', new CallbackNode(function () {}), true);

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

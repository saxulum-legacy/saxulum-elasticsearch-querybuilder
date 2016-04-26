<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ArrayNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $node = new ArrayNode();

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildren()
    {
        $node = new ArrayNode();
        $node->add(new ScalarNode('value1'));
        $node->add(new ScalarNode('value2'));

        $serialzed = [];
        $serialzed[] = 'value1';
        $serialzed[] = 'value2';

        self::assertEquals($serialzed, $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = new ArrayNode();
        $node->add(new ScalarNode(null));
        $node->add(new ScalarNode(null));

        self::assertNull($node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAllowDefault()
    {
        $node = new ArrayNode(true);
        $node->add(new ScalarNode(null));
        $node->add(new ScalarNode(null));

        self::assertEquals(null, $node->serialize());
    }

    public function testSerializeWithScalarChildrenWithNullValueAllowChildDefault()
    {
        $node = new ArrayNode();
        $node->add(new ScalarNode(null, true));
        $node->add(new ScalarNode(null, true));

        self::assertEquals([null, null], $node->serialize());
    }

    public function testSerializeWithCallbackChildrenWithNullValueAllowDefault()
    {
        $node = new ArrayNode(true);
        $node->add(new CallbackNode(function () {}));
        $node->add(new CallbackNode(function () {}));

        self::assertEquals(null, $node->serialize());
    }

    public function testSerializeWithCallbackChildrenWithNullValueAllowChildDefault()
    {
        $node = new ArrayNode();
        $node->add(new CallbackNode(function () {}, true));
        $node->add(new CallbackNode(function () {}, true));

        $serialzed = new \stdClass();
        $serialzed->key1 = null;
        $serialzed->key2 = null;

        self::assertEquals([null, null], $node->serialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node already got a parent!
     */
    public function testAddSameNodeTwice()
    {
        $node = new ArrayNode();

        $subNode = new ScalarNode('value');

        $node->add($subNode);
        $node->add($subNode);
    }
}

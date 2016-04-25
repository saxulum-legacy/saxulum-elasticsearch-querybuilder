<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ArrayNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testSerialize()
    {
        $node = new ArrayNode();

        self::assertNull($node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithAllowDefault()
    {
        $node = new ArrayNode(true);

        self::assertEquals([], $node->serialize());
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testSerializeWithScalarChildrenWithNullValue()
    {
        $node = new ArrayNode();
        $node->add(new ScalarNode(null));
        $node->add(new ScalarNode(null));

        self::assertNull($node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithScalarChildrenWithNullValueAllowDefault()
    {
        $node = new ArrayNode(true);
        $node->add(new ScalarNode(null));
        $node->add(new ScalarNode(null));

        self::assertEquals([], $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithScalarChildrenWithNullValueAllowChildDefault()
    {
        $node = new ArrayNode();
        $node->add(new ScalarNode(null, true));
        $node->add(new ScalarNode(null, true));

        self::assertEquals([null, null], $node->serialize());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node already got a parent!
     * @return void
     */
    public function testAddSameNodeTwice()
    {
        $node = new ArrayNode();

        $subNode = new ScalarNode('value');

        $node->add($subNode);
        $node->add($subNode);
    }
}

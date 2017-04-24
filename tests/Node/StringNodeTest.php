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
        $node = new StringNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new StringNode();

        self::assertNull($node->getDefault());
    }

    public function testSerializeWithValue()
    {
        $node = new StringNode('value');

        self::assertSame('value', $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = new StringNode();

        self::assertNull($node->serialize());
    }
}

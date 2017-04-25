<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\IntNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class IntNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new IntNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = new IntNode();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithValue()
    {
        $node = new IntNode(1);

        self::assertSame(1, $node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = new IntNode();

        self::assertNull($node->serialize());
    }
}

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\BoolNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class BoolNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new BoolNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new BoolNode();

        self::assertNull($node->getDefault());
    }

    public function testSerializeWithValue()
    {
        $node = new BoolNode(true);

        self::assertTrue($node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = new BoolNode();

        self::assertNull($node->serialize());
    }
}

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\NullNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class NullNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = NullNode::create();

        self::assertNull($node->getParent());

        $parent = ObjectNode::create();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = NullNode::create();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithNull()
    {
        $node = NullNode::create();

        self::assertNull($node->serialize());
    }
}

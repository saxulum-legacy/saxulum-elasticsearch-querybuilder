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
        $node = new NullNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testSerializeEmpty()
    {
        $node = new NullNode();

        self::assertNull($node->serializeEmpty());
    }

    public function testSerializeWithNull()
    {
        $node = new NullNode();

        self::assertNull($node->serialize());
    }
}

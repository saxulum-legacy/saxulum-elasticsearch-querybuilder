<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class ScalarNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new ScalarNode();

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new ScalarNode();

        self::assertNull($node->getDefault());
    }

    public function testSerializeWithString()
    {
        $node = new ScalarNode('string');

        self::assertSame('string', $node->serialize());
    }

    public function testSerializeWithFloat()
    {
        $node = new ScalarNode(3.14159);

        self::assertSame(3.14159, $node->serialize());
    }

    public function testSerializeWithInteger()
    {
        $node = new ScalarNode(3);

        self::assertSame(3, $node->serialize());
    }

    public function testSerializeWithBoolean()
    {
        $node = new ScalarNode(true);

        self::assertTrue($node->serialize());
    }

    public function testSerializeWithNull()
    {
        $node = new ScalarNode();

        self::assertNull($node->serialize());
    }
}

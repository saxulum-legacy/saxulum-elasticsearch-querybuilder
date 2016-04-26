<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ScalarNodeTest extends \PHPUnit_Framework_TestCase
{
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
}

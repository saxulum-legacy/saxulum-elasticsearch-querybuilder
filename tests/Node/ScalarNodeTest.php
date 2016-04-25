<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ScalarNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testAllowNullFalse()
    {
        $node = new ScalarNode(null);

        self::assertFalse($node->allowNull());
    }

    /**
     * @return void
     */
    public function testAllowNullTrue()
    {
        $node = new ScalarNode(null, true);

        self::assertTrue($node->allowNull());
    }

    /**
     * @return void
     */
    public function testSerializeWithString()
    {
        $node = new ScalarNode('string');

        self::assertSame('string', $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithFloat()
    {
        $node = new ScalarNode(3.14159);

        self::assertSame(3.14159, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithInteger()
    {
        $node = new ScalarNode(3);

        self::assertSame(3, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithBoolean()
    {
        $node = new ScalarNode(true);

        self::assertTrue($node->serialize());
    }
}

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ScalarNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testGetName()
    {
        $node = new ScalarNode('name', 'value');
        
        self::assertSame('name', $node->getName());
    }

    /**
     * @return void
     */
    public function testSerialize()
    {
        $node = new ScalarNode('name', 'value');

        self::assertInstanceOf('\stdClass', $node->serialize());

        $serialized = new \stdClass();
        $serialized->name = 'value';

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithValueNull()
    {
        $node = new ScalarNode('name', null);

        self::assertNull($node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithValueNullAndAllowNull()
    {
        $node = new ScalarNode('name', null, true);

        self::assertInstanceOf('\stdClass', $node->serialize());

        $serialized = new \stdClass();
        $serialized->name = null;

        self::assertEquals($serialized, $node->serialize());
    }
}

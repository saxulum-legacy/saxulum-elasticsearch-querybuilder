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

        self::assertSame(['name' => 'value'], $node->serialize());
    }
}

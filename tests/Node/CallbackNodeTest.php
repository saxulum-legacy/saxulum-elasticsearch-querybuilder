<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class CallbackNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefault()
    {
        $node = new CallbackNode(function () {});

        self::assertNull($node->getDefault());
    }

    public function testSerialize()
    {
        $node = new CallbackNode(function () {});

        self::assertNull($node->serialize());
    }

    public function testSerializeWithReturnValue()
    {
        $node = new CallbackNode(function () { return []; });

        self::assertSame([], $node->serialize());
    }
}

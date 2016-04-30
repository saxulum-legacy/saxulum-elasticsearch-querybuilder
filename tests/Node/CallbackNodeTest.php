<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class CallbackNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new CallbackNode(function () {});

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

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

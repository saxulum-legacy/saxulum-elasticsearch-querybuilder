<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ClosureNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

/**
 * @deprecated
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\ClosureNode
 * @covers Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 */
class ClosureNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParent()
    {
        $node = new ClosureNode(function () {});

        self::assertNull($node->getParent());

        $parent = new ObjectNode();
        $node->setParent($parent);

        self::assertSame($parent, $node->getParent());
    }

    public function testGetDefault()
    {
        $node = new ClosureNode(function () {});

        self::assertNull($node->getDefault());
    }

    public function testSerialize()
    {
        $node = new ClosureNode(function () {});

        self::assertNull($node->serialize());
    }

    public function testSerializeWithReturnValue()
    {
        $node = new ClosureNode(function () { return []; });

        self::assertSame([], $node->serialize());
    }
}

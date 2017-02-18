<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\NodeChildRelation;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\NodeChildRelation
 */
class NodeChildRelationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefault()
    {
        $node = new ScalarNode();
        $relation = new NodeChildRelation($node, true);

        self::assertSame($node, $relation->getNode());
        self::assertTrue($relation->isAllowDefault());
    }
}

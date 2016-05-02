<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Query;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Query
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $node = new ScalarNode();
        $expr = new Query($node);

        self::assertNull($expr->serialize());
        self::assertSame('null', $expr->json());
    }

    public function testWithValue()
    {
        $node = new ScalarNode('test');
        $expr = new Query($node);

        self::assertSame('test', $expr->serialize());
        self::assertSame('"test"', $expr->json(true));
    }
}

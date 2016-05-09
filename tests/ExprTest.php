<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Expr;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Expr
 */
class ExprTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $node = new ScalarNode();
        $expr = new Expr($node);

        self::assertSame($node, $expr->getNode());
        self::assertNull($expr->getKey());
        self::assertFalse($expr->isAllowDefault());
    }

    public function testWithKeyAndAllowDefault()
    {
        $node = new ScalarNode();
        $expr = (new Expr($node))->key('key')->allowDefault();

        self::assertSame($node, $expr->getNode());
        self::assertSame('key', $expr->getKey());
        self::assertTrue($expr->isAllowDefault());
    }
}

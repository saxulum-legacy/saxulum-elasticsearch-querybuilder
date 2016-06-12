<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Query;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\Query
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $query = new Query(new ObjectNode());

        self::assertNull($query->serialize());
        self::assertSame('', $query->json());
    }

    public function testSimple()
    {
        $node = new ObjectNode();
        $node->add('key', new ScalarNode('value'));

        $query = new Query($node);

        self::assertInstanceOf(\stdClass::class, $query->serialize());
        self::assertSame('{"key":"value"}', $query->json());
    }

    public function testBeautified()
    {
        $node = new ObjectNode();
        $node->add('key', new ScalarNode('value'));

        $query = new Query($node);

        self::assertInstanceOf(\stdClass::class, $query->serialize());

        $expected = <<<EOD
{
    "key": "value"
}
EOD;

        self::assertSame($expected, $query->json(true));
    }
}

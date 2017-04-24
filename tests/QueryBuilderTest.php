<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\QueryBuilder
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('match_all', $qb->objectNode(), true)
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->json());
    }

    public function testMatchAllWithoutAllowDefault()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
            ->addToObjectNode('match_all', $qb->objectNode())
        ;

        self::assertSame('', $qb->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('match', $qb->objectNode())
                    ->addToObjectNode('title', $qb->stringNode('elasticsearch'))
        ;

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', $qb->json());
    }

    public function testRange()
    {
        $qb = new QueryBuilder();
        $qb
        ->addToObjectNode('query', $qb->objectNode())
            ->addToObjectNode('range', $qb->objectNode())
                ->addToObjectNode('elements', $qb->objectNode())
                    ->addToObjectNode('gte', $qb->intNode(10))
                    ->addToObjectNode('lte', $qb->intNode(20))
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('exists', $qb->objectNode())
                    ->addToObjectNode('field', $qb->stringNode('text'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('bool', $qb->objectNode())
                    ->addToObjectNode('must_not', $qb->arrayNode())
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('exists', $qb->objectNode())
                                ->addToObjectNode('field', $qb->stringNode('text'))
        ;

        self::assertSame(
            '{"query":{"bool":{"must_not":[{"exists":{"field":"text"}}]}}}',
            $qb->json()
        );
    }

    public function testPrefix()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('prefix', $qb->objectNode())
                    ->addToObjectNode('title', $qb->stringNode('elastic'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('wildcard', $qb->objectNode())
                    ->addToObjectNode('title', $qb->stringNode('ela*c'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('regexp', $qb->objectNode())
                    ->addToObjectNode('title', $qb->stringNode('search$'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('fuzzy', $qb->objectNode())
                    ->addToObjectNode('title', $qb->objectNode())
                        ->addToObjectNode('value', $qb->stringNode('sea'))
                        ->addToObjectNode('fuzziness', $qb->intNode(2))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('type', $qb->objectNode())
                    ->addToObjectNode('value', $qb->stringNode('product'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('ids', $qb->objectNode())
                    ->addToObjectNode('type', $qb->stringNode('product'))
                    ->addToObjectNode('values', $qb->arrayNode())
                        ->addToArrayNode($qb->intNode(1))
                        ->addToArrayNode($qb->intNode(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->json());
    }

    public function testBoolTerm()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('is_published', $qb->boolNode(true))
        ;

        self::assertSame('{"query":{"term":{"is_published":true}}}', $qb->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('bool', $qb->objectNode())
                    ->addToObjectNode('must', $qb->objectNode())
                        ->addToObjectNode('term', $qb->objectNode())
                            ->addToObjectNode('user', $qb->stringNode('kimchy'))
                        ->end()
                    ->end()
                    ->addToObjectNode('filter', $qb->objectNode())
                        ->addToObjectNode('term', $qb->objectNode())
                            ->addToObjectNode('tag', $qb->stringNode('tech'))
                        ->end()
                    ->end()
                    ->addToObjectNode('must_not', $qb->objectNode())
                        ->addToObjectNode('range', $qb->objectNode())
                            ->addToObjectNode('age', $qb->objectNode())
                                ->addToObjectNode('from', $qb->intNode(10))
                                ->addToObjectNode('to', $qb->intNode(20))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('should', $qb->arrayNode())
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('term', $qb->objectNode())
                                ->addToObjectNode('tag', $qb->stringNode('wow'))
                            ->end()
                        ->end()
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('term', $qb->objectNode())
                                ->addToObjectNode('tag', $qb->stringNode('elasticsearch'))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('minimum_should_match', $qb->intNode(1))
                    ->addToObjectNode('boost', $qb->floatNode(1.1))
        ;

        $expected = <<<EOD
{
    "query": {
        "bool": {
            "must": {
                "term": {
                    "user": "kimchy"
                }
            },
            "filter": {
                "term": {
                    "tag": "tech"
                }
            },
            "must_not": {
                "range": {
                    "age": {
                        "from": 10,
                        "to": 20
                    }
                }
            },
            "should": [
                {
                    "term": {
                        "tag": "wow"
                    }
                },
                {
                    "term": {
                        "tag": "elasticsearch"
                    }
                }
            ],
            "minimum_should_match": 1,
            "boost": 1.1
        }
    }
}
EOD;

        self::assertSame($expected, $qb->json(true));
    }

    public function testEmptyQuery()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('bool', $qb->objectNode())
                    ->addToObjectNode('must', $qb->arrayNode())
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('terms', $qb->objectNode())
                                ->addToObjectNode('field', $qb->arrayNode())
                                    ->addToArrayNode($qb->stringNode(null))
        ;

        self::assertSame('', $qb->json());
    }

    public function testDeprecatedScalarNode()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('field', $qb->scalarNode('value'));

        self::assertSame('{"query":{"term":{"field":"value"}}}', $qb->json());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToArrayNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
     */
    public function testAddToArrayNodeIfItsNotAnActiveArrayNode()
    {
        $qb = new QueryBuilder();
        $qb->addToArrayNode($qb->stringNode());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToObjectNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode
     */
    public function testAddToObjectNodeIfItsNotAnActiveObjectNode()
    {
        $qb = new QueryBuilder();
        $qb->addToObjectNode('key', $qb->arrayNode())
            ->addToObjectNode('key', $qb->stringNode())
        ;
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call end on main node
     */
    public function testToManyEnd()
    {
        (new QueryBuilder())->end();
    }
}

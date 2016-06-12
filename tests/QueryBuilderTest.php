<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ClosureNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

/**
 * @covers Saxulum\ElasticSearchQueryBuilder\QueryBuilder
 */
class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('match_all', new ObjectNode(), true)
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->query()->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('match', new ObjectNode())
                    ->addToObjectNode('title', new ScalarNode('elasticsearch'))
        ;

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', $qb->query()->json());
    }

    /**
     * @dataProvider getQuerySamples
     */
    public function testMatchWithMatchAllFallback($expectedResult, $query)
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ClosureNode(function () use ($query) {
                $qb = new QueryBuilder();
                $qb
                    ->addToObjectNode('match', new ObjectNode())
                        ->addToObjectNode('title', new ScalarNode($query))
                ;

                if (null !== $serialzed = $qb->query()->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb
                    ->addToObjectNode('match_all', new ObjectNode(), true)
                ;

                return $qb->query()->serialize();
            }))
        ;

        self::assertSame($expectedResult, $qb->query()->json());
    }

    /**
     * @return array
     */
    public function getQuerySamples()
    {
        return [
            ['{"query":{"match":{"title":"elasticsearch"}}}', 'elasticsearch'],
            ['{"query":{"match_all":{}}}', null],
        ];
    }

    public function testRange()
    {
        $qb = new QueryBuilder();
        $qb
        ->addToObjectNode('query', new ObjectNode())
            ->addToObjectNode('range', new ObjectNode())
                ->addToObjectNode('elements', new ObjectNode())
                    ->addToObjectNode('gte', new ScalarNode(10))
                    ->addToObjectNode('lte', new ScalarNode(20))
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->query()->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('exists', new ObjectNode())
                    ->addToObjectNode('field', new ScalarNode('text'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->query()->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('bool', new ObjectNode())
                    ->addToObjectNode('must_not', new ObjectNode())
                        ->addToObjectNode('exists', new ObjectNode())
                            ->addToObjectNode('field', new ScalarNode('text'))
        ;

        self::assertSame(
            '{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}',
            $qb->query()->json()
        );
    }

    public function testPrefix()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('prefix', new ObjectNode())
                    ->addToObjectNode('title', new ScalarNode('elastic'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->query()->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('wildcard', new ObjectNode())
                    ->addToObjectNode('title', new ScalarNode('ela*c'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->query()->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('regexp', new ObjectNode())
                    ->addToObjectNode('title', new ScalarNode('search$'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->query()->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('fuzzy', new ObjectNode())
                    ->addToObjectNode('title', new ObjectNode())
                        ->addToObjectNode('value', new ScalarNode('sea'))
                        ->addToObjectNode('fuzziness', new ScalarNode(2))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->query()->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('type', new ObjectNode())
                    ->addToObjectNode('value', new ScalarNode('product'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->query()->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('ids', new ObjectNode())
                    ->addToObjectNode('type', new ScalarNode('product'))
                    ->addToObjectNode('values', new ArrayNode())
                        ->addToArrayNode(new ScalarNode(1))
                        ->addToArrayNode(new ScalarNode(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->query()->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', new ObjectNode())
                ->addToObjectNode('bool', new ObjectNode())
                    ->addToObjectNode('must', new ObjectNode())
                        ->addToObjectNode('term', new ObjectNode())
                            ->addToObjectNode('user', new ScalarNode('kimchy'))
                        ->end()
                    ->end()
                    ->addToObjectNode('filter', new ObjectNode())
                        ->addToObjectNode('term', new ObjectNode())
                            ->addToObjectNode('tag', new ScalarNode('tech'))
                        ->end()
                    ->end()
                    ->addToObjectNode('must_not', new ObjectNode())
                        ->addToObjectNode('range', new ObjectNode())
                            ->addToObjectNode('age', new ObjectNode())
                                ->addToObjectNode('from', new ScalarNode(10))
                                ->addToObjectNode('to', new ScalarNode(20))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('should', new ArrayNode())
                        ->addToArrayNode(new ObjectNode())
                            ->addToObjectNode('term', new ObjectNode())
                                ->addToObjectNode('tag', new ScalarNode('wow'))
                            ->end()
                        ->end()
                        ->addToArrayNode(new ObjectNode())
                            ->addToObjectNode('term', new ObjectNode())
                                ->addToObjectNode('tag', new ScalarNode('elasticsearch'))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('minimum_should_match', new ScalarNode(1))
                    ->addToObjectNode('boost', new ScalarNode(1))
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
            "boost": 1
        }
    }
}
EOD;

        self::assertSame($expected, $qb->query()->json(true));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToArrayNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
     */
    public function testAddToArrayNodeIfItsNotAnActiveArrayNode()
    {
        $qb = new QueryBuilder();
        $qb->addToArrayNode(new ScalarNode());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToObjectNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode
     */
    public function testAddToObjectNodeIfItsNotAnActiveObjectNode()
    {
        $qb = new QueryBuilder();
        $qb->addToObjectNode('key', new ArrayNode())
            ->addToObjectNode('key', new ScalarNode())
        ;
    }
}

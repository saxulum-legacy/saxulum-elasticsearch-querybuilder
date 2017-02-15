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
                    ->addToObjectNode('title', $qb->scalarNode('elasticsearch'))
        ;

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', $qb->json());
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
                    ->addToObjectNode('match', $qb->objectNode())
                        ->addToObjectNode('title', $qb->scalarNode($query))
                ;

                if (null !== $serialzed = $qb->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb
                    ->addToObjectNode('match_all', $qb->objectNode(), true)
                ;

                return $qb->serialize();
            }))
        ;

        self::assertSame($expectedResult, $qb->json());
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
        ->addToObjectNode('query', $qb->objectNode())
            ->addToObjectNode('range', $qb->objectNode())
                ->addToObjectNode('elements', $qb->objectNode())
                    ->addToObjectNode('gte', $qb->scalarNode(10))
                    ->addToObjectNode('lte', $qb->scalarNode(20))
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('exists', $qb->objectNode())
                    ->addToObjectNode('field', $qb->scalarNode('text'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('bool', $qb->objectNode())
                    ->addToObjectNode('must_not', $qb->objectNode())
                        ->addToObjectNode('exists', $qb->objectNode())
                            ->addToObjectNode('field', $qb->scalarNode('text'))
        ;

        self::assertSame(
            '{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}',
            $qb->json()
        );
    }

    public function testPrefix()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('prefix', $qb->objectNode())
                    ->addToObjectNode('title', $qb->scalarNode('elastic'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('wildcard', $qb->objectNode())
                    ->addToObjectNode('title', $qb->scalarNode('ela*c'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('regexp', $qb->objectNode())
                    ->addToObjectNode('title', $qb->scalarNode('search$'))
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
                        ->addToObjectNode('value', $qb->scalarNode('sea'))
                        ->addToObjectNode('fuzziness', $qb->scalarNode(2))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('type', $qb->objectNode())
                    ->addToObjectNode('value', $qb->scalarNode('product'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('ids', $qb->objectNode())
                    ->addToObjectNode('type', $qb->scalarNode('product'))
                    ->addToObjectNode('values', $qb->arrayNode())
                        ->addToArrayNode($qb->scalarNode(1))
                        ->addToArrayNode($qb->scalarNode(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->addToObjectNode('query', $qb->objectNode())
                ->addToObjectNode('bool', $qb->objectNode())
                    ->addToObjectNode('must', $qb->objectNode())
                        ->addToObjectNode('term', $qb->objectNode())
                            ->addToObjectNode('user', $qb->scalarNode('kimchy'))
                        ->end()
                    ->end()
                    ->addToObjectNode('filter', $qb->objectNode())
                        ->addToObjectNode('term', $qb->objectNode())
                            ->addToObjectNode('tag', $qb->scalarNode('tech'))
                        ->end()
                    ->end()
                    ->addToObjectNode('must_not', $qb->objectNode())
                        ->addToObjectNode('range', $qb->objectNode())
                            ->addToObjectNode('age', $qb->objectNode())
                                ->addToObjectNode('from', $qb->scalarNode(10))
                                ->addToObjectNode('to', $qb->scalarNode(20))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('should', $qb->arrayNode())
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('term', $qb->objectNode())
                                ->addToObjectNode('tag', $qb->scalarNode('wow'))
                            ->end()
                        ->end()
                        ->addToArrayNode($qb->objectNode())
                            ->addToObjectNode('term', $qb->objectNode())
                                ->addToObjectNode('tag', $qb->scalarNode('elasticsearch'))
                            ->end()
                        ->end()
                    ->end()
                    ->addToObjectNode('minimum_should_match', $qb->scalarNode(1))
                    ->addToObjectNode('boost', $qb->scalarNode(1))
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

        self::assertSame($expected, $qb->json(true));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToArrayNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
     */
    public function testAddToArrayNodeIfItsNotAnActiveArrayNode()
    {
        $qb = new QueryBuilder();
        $qb->addToArrayNode($qb->scalarNode());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage You cannot call addToObjectNode on node type: Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode
     */
    public function testAddToObjectNodeIfItsNotAnActiveObjectNode()
    {
        $qb = new QueryBuilder();
        $qb->addToObjectNode('key', $qb->arrayNode())
            ->addToObjectNode('key', $qb->scalarNode())
        ;
    }
}

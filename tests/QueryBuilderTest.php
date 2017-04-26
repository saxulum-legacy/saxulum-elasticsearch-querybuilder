<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

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
            ->add('query', $qb->objectNode())
                ->add('match_all', $qb->objectNode(true))
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->json());
    }

    public function testMatchAllWithoutAllowSerializeEmpty()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('match_all', $qb->objectNode())
        ;

        self::assertSame('', $qb->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('match', $qb->objectNode())
                    ->add('title', $qb->stringNode('elasticsearch'))
        ;

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', $qb->json());
    }

    public function testRange()
    {
        $qb = new QueryBuilder();
        $qb
        ->add('query', $qb->objectNode())
            ->add('range', $qb->objectNode())
                ->add('elements', $qb->objectNode())
                    ->add('gte', $qb->intNode(10))
                    ->add('lte', $qb->intNode(20))
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('exists', $qb->objectNode())
                    ->add('field', $qb->stringNode('text'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('bool', $qb->objectNode())
                    ->add('must_not', $qb->arrayNode())
                        ->add($qb->objectNode())
                            ->add('exists', $qb->objectNode())
                                ->add('field', $qb->stringNode('text'))
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
            ->add('query', $qb->objectNode())
                ->add('prefix', $qb->objectNode())
                    ->add('title', $qb->stringNode('elastic'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('wildcard', $qb->objectNode())
                    ->add('title', $qb->stringNode('ela*c'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('regexp', $qb->objectNode())
                    ->add('title', $qb->stringNode('search$'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('fuzzy', $qb->objectNode())
                    ->add('title', $qb->objectNode())
                        ->add('value', $qb->stringNode('sea'))
                        ->add('fuzziness', $qb->intNode(2))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('type', $qb->objectNode())
                    ->add('value', $qb->stringNode('product'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('ids', $qb->objectNode())
                    ->add('type', $qb->stringNode('product'))
                    ->add('values', $qb->arrayNode())
                        ->add($qb->intNode(1))
                        ->add($qb->intNode(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->json());
    }

    public function testBoolTerm()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('term', $qb->objectNode())
                    ->add('is_published', $qb->boolNode(true))
        ;

        self::assertSame('{"query":{"term":{"is_published":true}}}', $qb->json());
    }

    public function testNullNode()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('term', $qb->objectNode())
                    ->add('field', $qb->nullNode())
        ;

        self::assertSame('{"query":{"term":{"field":null}}}', $qb->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->add('query', $qb->objectNode())
                ->add('bool', $qb->objectNode())
                    ->add('must', $qb->objectNode())
                        ->add('term', $qb->objectNode())
                            ->add('user', $qb->stringNode('kimchy'))
                        ->end()
                    ->end()
                    ->add('filter', $qb->objectNode())
                        ->add('term', $qb->objectNode())
                            ->add('tag', $qb->stringNode('tech'))
                        ->end()
                    ->end()
                    ->add('must_not', $qb->objectNode())
                        ->add('range', $qb->objectNode())
                            ->add('age', $qb->objectNode())
                                ->add('from', $qb->intNode(10))
                                ->add('to', $qb->intNode(20))
                            ->end()
                        ->end()
                    ->end()
                    ->add('should', $qb->arrayNode())
                        ->add($qb->objectNode())
                            ->add('term', $qb->objectNode())
                                ->add('tag', $qb->stringNode('wow'))
                            ->end()
                        ->end()
                        ->add($qb->objectNode())
                            ->add('term', $qb->objectNode())
                                ->add('tag', $qb->stringNode('elasticsearch'))
                            ->end()
                        ->end()
                    ->end()
                    ->add('minimum_should_match', $qb->intNode(1))
                    ->add('boost', $qb->floatNode(1.1))
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
            ->add('query', $qb->objectNode())
                ->add('bool', $qb->objectNode())
                    ->add('must', $qb->arrayNode())
                        ->add($qb->objectNode())
                            ->add('terms', $qb->objectNode())
                                ->add('field', $qb->arrayNode())
                                    ->add($qb->stringNode(null))
        ;

        self::assertSame('', $qb->json());
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
        $qb
            ->addToObjectNode('key', $qb->arrayNode())
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

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

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
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('match_all')->allowDefault())
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->query()->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('match'))
                    ->add($qb->sca('elasticsearch')->key('title'))
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
            ->add($qb->clo(function () use ($query) {
                $qb = new QueryBuilder();
                $qb->add($qb->obj()->key('match'))->add($qb->sca($query)->key('title'));

                if (null !== $serialzed = $qb->query()->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb->add($qb->obj()->key('match_all')->allowDefault());

                return $qb->query()->serialize();
            })->key('query'))
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
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('range'))
                    ->add($qb->obj()->key('elements'))
                        ->add($qb->sca(10)->key('gte'))
                        ->add($qb->sca(20)->key('lte'))

        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->query()->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('exists'))
                    ->add($qb->sca('text')->key('field'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->query()->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('bool'))
                    ->add($qb->obj()->key('must_not'))
                        ->add($qb->obj()->key('exists'))
                            ->add($qb->sca('text')->key('field'))
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
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('prefix'))
                    ->add($qb->sca('elastic')->key('title'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->query()->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('wildcard'))
                    ->add($qb->sca('ela*c')->key('title'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->query()->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('regexp'))
                    ->add($qb->sca('search$')->key('title'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->query()->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('fuzzy'))
                    ->add($qb->obj()->key('title'))
                        ->add($qb->sca('sea')->key('value'))
                        ->add($qb->sca(2)->key('fuzziness'))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->query()->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('type'))
                    ->add($qb->sca('product')->key('value'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->query()->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('ids'))
                    ->add($qb->sca('product')->key('type'))
                    ->add($qb->arr()->key('values'))
                        ->add($qb->sca(1))
                        ->add($qb->sca(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->query()->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->obj()->key('query'))
                ->add($qb->obj()->key('bool'))
                    ->add($qb->obj()->key('must'))
                        ->add($qb->obj()->key('term'))
                            ->add($qb->sca('kimchy')->key('user'))
                        ->end()
                    ->end()
                    ->add($qb->obj()->key('filter'))
                        ->add($qb->obj()->key('term'))
                            ->add($qb->sca('tech')->key('tag'))
                        ->end()
                    ->end()
                    ->add($qb->obj()->key('must_not'))
                        ->add($qb->obj()->key('range'))
                            ->add($qb->obj()->key('age'))
                                ->add($qb->sca(10)->key('from'))
                                ->add($qb->sca(20)->key('to'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->arr()->key('should'))
                        ->add($qb->obj())
                            ->add($qb->obj()->key('term'))
                                ->add($qb->sca('wow')->key('tag'))
                            ->end()
                        ->end()
                        ->add($qb->obj())
                            ->add($qb->obj()->key('term'))
                                ->add($qb->sca('elasticsearch')->key('tag'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->sca(1)->key('minimum_should_match'))
                    ->add($qb->sca(1)->key('boost'))
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
}

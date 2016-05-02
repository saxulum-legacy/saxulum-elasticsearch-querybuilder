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
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('match_all')->allowDefault())
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->query()->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('match'))
                    ->add($qb->s('elasticsearch')->key('title'))
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
            ->add($qb->c(function () use ($query) {
                $qb = new QueryBuilder();
                $qb->add($qb->o()->key('match'))->add($qb->s($query)->key('title'));

                if (null !== $serialzed = $qb->query()->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb->add($qb->o()->key('match_all')->allowDefault());

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
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('range'))
                    ->add($qb->o()->key('elements'))
                        ->add($qb->s(10)->key('gte'))
                        ->add($qb->s(20)->key('lte'))

        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->query()->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('exists'))
                    ->add($qb->s('text')->key('field'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->query()->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('bool'))
                    ->add($qb->o()->key('must_not'))
                        ->add($qb->o()->key('exists'))
                            ->add($qb->s('text')->key('field'))
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
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('prefix'))
                    ->add($qb->s('elastic')->key('title'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->query()->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('wildcard'))
                    ->add($qb->s('ela*c')->key('title'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->query()->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('regexp'))
                    ->add($qb->s('search$')->key('title'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->query()->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('fuzzy'))
                    ->add($qb->o()->key('title'))
                        ->add($qb->s('sea')->key('value'))
                        ->add($qb->s(2)->key('fuzziness'))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->query()->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('type'))
                    ->add($qb->s('product')->key('value'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->query()->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('ids'))
                    ->add($qb->s('product')->key('type'))
                    ->add($qb->a()->key('values'))
                        ->add($qb->s(1))
                        ->add($qb->s(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->query()->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('bool'))
                    ->add($qb->o()->key('must'))
                        ->add($qb->o()->key('term'))
                            ->add($qb->s('kimchy')->key('user'))
                        ->end()
                    ->end()
                    ->add($qb->o()->key('filter'))
                        ->add($qb->o()->key('term'))
                            ->add($qb->s('tech')->key('tag'))
                        ->end()
                    ->end()
                    ->add($qb->o()->key('must_not'))
                        ->add($qb->o()->key('range'))
                            ->add($qb->o()->key('age'))
                                ->add($qb->s(10)->key('from'))
                                ->add($qb->s(20)->key('to'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->a()->key('should'))
                        ->add($qb->o())
                            ->add($qb->o()->key('term'))
                                ->add($qb->s('wow')->key('tag'))
                            ->end()
                        ->end()
                        ->add($qb->o())
                            ->add($qb->o()->key('term'))
                                ->add($qb->s('elasticsearch')->key('tag'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->s(1)->key('minimum_should_match'))
                    ->add($qb->s(1)->key('boost'))
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

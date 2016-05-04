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
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('match_all')->d())
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->query()->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('match'))
                    ->add($qb->s('elasticsearch')->k('title'))
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
                $qb->add($qb->o()->k('match'))->add($qb->s($query)->k('title'));

                if (null !== $serialzed = $qb->query()->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb->add($qb->o()->k('match_all')->d());

                return $qb->query()->serialize();
            })->k('query'))
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
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('range'))
                    ->add($qb->o()->k('elements'))
                        ->add($qb->s(10)->k('gte'))
                        ->add($qb->s(20)->k('lte'))

        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->query()->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('exists'))
                    ->add($qb->s('text')->k('field'))
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $qb->query()->json());
    }

    public function testNotExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('bool'))
                    ->add($qb->o()->k('must_not'))
                        ->add($qb->o()->k('exists'))
                            ->add($qb->s('text')->k('field'))
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
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('prefix'))
                    ->add($qb->s('elastic')->k('title'))
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->query()->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('wildcard'))
                    ->add($qb->s('ela*c')->k('title'))
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->query()->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('regexp'))
                    ->add($qb->s('search$')->k('title'))
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $qb->query()->json());
    }

    public function testFuzzy()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('fuzzy'))
                    ->add($qb->o()->k('title'))
                        ->add($qb->s('sea')->k('value'))
                        ->add($qb->s(2)->k('fuzziness'))
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->query()->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('type'))
                    ->add($qb->s('product')->k('value'))
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->query()->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('ids'))
                    ->add($qb->s('product')->k('type'))
                    ->add($qb->a()->k('values'))
                        ->add($qb->s(1))
                        ->add($qb->s(2))
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->query()->json());
    }

    public function testComplex()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('bool'))
                    ->add($qb->o()->k('must'))
                        ->add($qb->o()->k('term'))
                            ->add($qb->s('kimchy')->k('user'))
                        ->end()
                    ->end()
                    ->add($qb->o()->k('filter'))
                        ->add($qb->o()->k('term'))
                            ->add($qb->s('tech')->k('tag'))
                        ->end()
                    ->end()
                    ->add($qb->o()->k('must_not'))
                        ->add($qb->o()->k('range'))
                            ->add($qb->o()->k('age'))
                                ->add($qb->s(10)->k('from'))
                                ->add($qb->s(20)->k('to'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->a()->k('should'))
                        ->add($qb->o())
                            ->add($qb->o()->k('term'))
                                ->add($qb->s('wow')->k('tag'))
                            ->end()
                        ->end()
                        ->add($qb->o())
                            ->add($qb->o()->k('term'))
                                ->add($qb->s('elasticsearch')->k('tag'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->s(1)->k('minimum_should_match'))
                    ->add($qb->s(1)->k('boost'))
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

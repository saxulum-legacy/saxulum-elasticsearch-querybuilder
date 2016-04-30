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
                ->add($qb->o()->key('match_all')->allowDefault())->end()
            ->end()
        ;

        self::assertSame('{"query":{"match_all":{}}}', $qb->query()->json());
    }

    public function testMatch()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('match'))
                    ->add($qb->s('elasticsearch')->key('title'))->end()
                ->end()
            ->end()
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
                $qb->add($qb->o()->key('match'))->add($qb->s($query)->key('title'))->end()->end();

                if (null !== $serialzed = $qb->query()->serialize()) {
                    return $serialzed;
                }

                $qb = new QueryBuilder();
                $qb->add($qb->o()->key('match_all')->allowDefault())->end();

                return $qb->query()->serialize();
            })->key('query'))->end()
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
                        ->add($qb->s(10)->key('gte'))->end()
                        ->add($qb->s(20)->key('lte'))->end()
                    ->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $qb->query()->json());
    }

    public function testExists()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('exists'))
                    ->add($qb->s('text')->key('field'))->end()
                ->end()
            ->end()
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
                            ->add($qb->s('text')->key('field'))->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
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
                    ->add($qb->s('elastic')->key('title'))->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $qb->query()->json());
    }

    public function testWildcard()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('wildcard'))
                    ->add($qb->s('ela*c')->key('title'))->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $qb->query()->json());
    }

    public function testRegexp()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('regexp'))
                    ->add($qb->s('search$')->key('title'))->end()
                ->end()
            ->end()
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
                        ->add($qb->s('sea')->key('value'))->end()
                        ->add($qb->s(2)->key('fuzziness'))->end()
                    ->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $qb->query()->json());
    }

    public function testType()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('type'))
                    ->add($qb->s('product')->key('value'))->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', $qb->query()->json());
    }

    public function testIds()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('ids'))
                    ->add($qb->s('product')->key('type'))->end()
                    ->add($qb->a()->key('values'))
                        ->add($qb->s(1))->end()
                        ->add($qb->s(2))->end()
                    ->end()
                ->end()
            ->end()
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $qb->query()->json());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Node does not support a child!
     */
    public function testAddOnScalar()
    {
        $qb = new QueryBuilder();
        $qb
            ->add($qb->s())->add($qb->s())->end()->end()
        ;
    }
}

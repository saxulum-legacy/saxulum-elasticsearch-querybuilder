<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('match_all', new ObjectNode(true))
            )
        ;

        self::assertSame('{"query":{"match_all":{}}}', json_encode($query->serialize()));
    }

    public function testMatch()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('match', (new ObjectNode())
                    ->add('title', new ScalarNode('elasticsearch'))
                )
            )
        ;

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', json_encode($query->serialize()));
    }

    public function testRange()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('range', (new ObjectNode())
                    ->add('elements', (new ObjectNode())
                        ->add('gte', new ScalarNode(10))
                        ->add('lte', new ScalarNode(20))
                    )
                )
            )
        ;

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', json_encode($query->serialize()));
    }

    public function testExists()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('exists', (new ObjectNode())
                    ->add('field', new ScalarNode('text'))
                )
            )
        ;

        self::assertSame('{"query":{"exists":{"field":"text"}}}', json_encode($query->serialize()));
    }

    public function testNotExists()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('bool', (new ObjectNode())
                    ->add('must_not', (new ObjectNode())
                        ->add('exists', (new ObjectNode())
                            ->add('field', new ScalarNode('text'))
                        )
                    )
                )
            )
        ;

        self::assertSame(
            '{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}',
            json_encode($query->serialize())
        );
    }

    public function testPrefix()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('prefix', (new ObjectNode())
                    ->add('title', new ScalarNode('elastic'))
                )
            )
        ;

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', json_encode($query->serialize()));
    }

    public function testWildcard()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('wildcard', (new ObjectNode())
                    ->add('title', new ScalarNode('ela*c'))
                )
            )
        ;

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', json_encode($query->serialize()));
    }

    public function testRegexp()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('regexp', (new ObjectNode())
                    ->add('title', new ScalarNode('search$'))
                )
            )
        ;

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', json_encode($query->serialize()));
    }

    public function testFuzzy()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('fuzzy', (new ObjectNode())
                    ->add('title', (new ObjectNode())
                        ->add('value', new ScalarNode('sea'))
                        ->add('fuzziness', new ScalarNode(2))
                    )
                )
            )
        ;

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', json_encode($query->serialize()));
    }

    public function testType()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('type', (new ObjectNode())
                    ->add('value', new ScalarNode('product'))
                )
            )
        ;

        self::assertSame('{"query":{"type":{"value":"product"}}}', json_encode($query->serialize()));
    }

    public function testIds()
    {
        $query = (new ObjectNode())
            ->add('query', (new ObjectNode())
                ->add('ids', (new ObjectNode())
                    ->add('type', new ScalarNode('product'))
                    ->add('values', (new ArrayNode())
                        ->add(new ScalarNode(1))
                        ->add(new ScalarNode(2))
                    )
                )
            )
        ;

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', json_encode($query->serialize()));
    }
}

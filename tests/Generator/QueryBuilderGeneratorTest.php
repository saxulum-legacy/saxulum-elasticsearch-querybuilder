<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Generator;

use PhpParser\PrettyPrinter\Standard as PhpGenerator;
use Saxulum\ElasticSearchQueryBuilder\Generator\QueryBuilderGenerator;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Generator\QueryBuilderGenerator
 */
class QueryBuilderGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('match_all', $queryBuilder->objectNode())->end()
    ->end();
EOD;

        $json = '{"query":{"match_all":{}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testMatch()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('match', $queryBuilder->objectNode())
            ->addToObjectNode('title', $queryBuilder->scalarNode('elasticsearch'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"match":{"title":"elasticsearch"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testRange()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('range', $queryBuilder->objectNode())
            ->addToObjectNode('elements', $queryBuilder->objectNode())
                ->addToObjectNode('gte', $queryBuilder->scalarNode(10))
                ->addToObjectNode('lte', $queryBuilder->scalarNode(20))
            ->end()
        ->end()
    ->end();
EOD;

        $json = '{"query":{"range":{"elements":{"gte":10,"lte":20}}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testExists()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('exists', $queryBuilder->objectNode())
            ->addToObjectNode('field', $queryBuilder->scalarNode('text'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"exists":{"field":"text"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testNotExists()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('bool', $queryBuilder->objectNode())
            ->addToObjectNode('must_not', $queryBuilder->objectNode())
                ->addToObjectNode('exists', $queryBuilder->objectNode())
                    ->addToObjectNode('field', $queryBuilder->scalarNode('text'))
                ->end()
            ->end()
        ->end()
    ->end();
EOD;

        $json = '{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testPrefix()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('prefix', $queryBuilder->objectNode())
            ->addToObjectNode('title', $queryBuilder->scalarNode('elastic'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"prefix":{"title":"elastic"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testWildcard()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('wildcard', $queryBuilder->objectNode())
            ->addToObjectNode('title', $queryBuilder->scalarNode('ela*c'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"wildcard":{"title":"ela*c"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testRegexp()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('regexp', $queryBuilder->objectNode())
            ->addToObjectNode('title', $queryBuilder->scalarNode('search$'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"regexp":{"title":"search$"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testFuzzy()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('fuzzy', $queryBuilder->objectNode())
            ->addToObjectNode('title', $queryBuilder->objectNode())
                ->addToObjectNode('value', $queryBuilder->scalarNode('sea'))
                ->addToObjectNode('fuzziness', $queryBuilder->scalarNode(2))
            ->end()
        ->end()
    ->end();
EOD;

        $json = '{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testType()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('type', $queryBuilder->objectNode())
            ->addToObjectNode('value', $queryBuilder->scalarNode('product'))
        ->end()
    ->end();
EOD;

        $json = '{"query":{"type":{"value":"product"}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testIds()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('ids', $queryBuilder->objectNode())
            ->addToObjectNode('type', $queryBuilder->scalarNode('product'))
            ->addToObjectNode('values', $queryBuilder->arrayNode())
                ->addToArrayNode($queryBuilder->scalarNode(1))
                ->addToArrayNode($queryBuilder->scalarNode(2))
            ->end()
        ->end()
    ->end();
EOD;

        $json = '{"query":{"ids":{"type":"product","values":[1,2]}}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testComplex()
    {
        $expect = <<<'EOD'
$queryBuilder = new QueryBuilder();
$queryBuilder
    ->addToObjectNode('query', $queryBuilder->objectNode())
        ->addToObjectNode('bool', $queryBuilder->objectNode())
            ->addToObjectNode('must', $queryBuilder->objectNode())
                ->addToObjectNode('term', $queryBuilder->objectNode())
                    ->addToObjectNode('user', $queryBuilder->scalarNode('kimchy'))
                ->end()
            ->end()
            ->addToObjectNode('filter', $queryBuilder->objectNode())
                ->addToObjectNode('term', $queryBuilder->objectNode())
                    ->addToObjectNode('tag', $queryBuilder->scalarNode('tech'))
                ->end()
            ->end()
            ->addToObjectNode('must_not', $queryBuilder->objectNode())
                ->addToObjectNode('range', $queryBuilder->objectNode())
                    ->addToObjectNode('age', $queryBuilder->objectNode())
                        ->addToObjectNode('from', $queryBuilder->scalarNode(10))
                        ->addToObjectNode('to', $queryBuilder->scalarNode(20))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('should', $queryBuilder->arrayNode())
                ->addToArrayNode($queryBuilder->objectNode())
                    ->addToObjectNode('term', $queryBuilder->objectNode())
                        ->addToObjectNode('tag', $queryBuilder->scalarNode('wow'))
                    ->end()
                ->end()
                ->addToArrayNode($queryBuilder->objectNode())
                    ->addToObjectNode('term', $queryBuilder->objectNode())
                        ->addToObjectNode('tag', $queryBuilder->scalarNode('elasticsearch'))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('minimum_should_match', $queryBuilder->scalarNode(1))
            ->addToObjectNode('boost', $queryBuilder->scalarNode(1.2))
            ->addToObjectNode('enabled', $queryBuilder->scalarNode(true))
            ->addToObjectNode('relation', $queryBuilder->scalarNode(null))
            ->addToObjectNode('array', $queryBuilder->arrayNode())
                ->addToArrayNode($queryBuilder->arrayNode())
                    ->addToArrayNode($queryBuilder->objectNode())
                        ->addToObjectNode('term', $queryBuilder->objectNode())
                            ->addToObjectNode('tag', $queryBuilder->scalarNode('wow'))
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
    ->end();
EOD;

        $json = <<<EOD
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
            "boost": 1.2,
            "enabled": true,
            "relation": null,
            "array": [
                [
                    {
                        "term": {
                            "tag": "wow"
                        }
                    }
                ]
            ]
        }
    }
}
EOD;

        $generator = new QueryBuilderGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testWithInvalidJson()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Message: Syntax error, query: {"query":{"ids":{"type":"product","values":[1,2]}}');

        $json = '{"query":{"ids":{"type":"product","values":[1,2]}}';

        $generator = new QueryBuilderGenerator(new PhpGenerator());
        $generator->generateByJson($json);
    }
}

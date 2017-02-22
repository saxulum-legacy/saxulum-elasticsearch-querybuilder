<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Generator;

use PhpParser\PrettyPrinter\Standard as PhpGenerator;
use Saxulum\ElasticSearchQueryBuilder\Generator\NodeGenerator;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Generator\NodeGenerator
 */
class NodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match_all', new ObjectNode())
    );
EOD;

        $json = '{"query":{"match_all":{}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testMatch()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match', (new ObjectNode())
            ->add('title', new ScalarNode('elasticsearch'))
        )
    );
EOD;

        $json = '{"query":{"match":{"title":"elasticsearch"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testRange()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('range', (new ObjectNode())
            ->add('elements', (new ObjectNode())
                ->add('gte', new ScalarNode(10))
                ->add('lte', new ScalarNode(20))
            )
        )
    );
EOD;

        $json = '{"query":{"range":{"elements":{"gte":10,"lte":20}}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testExists()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('exists', (new ObjectNode())
            ->add('field', new ScalarNode('text'))
        )
    );
EOD;

        $json = '{"query":{"exists":{"field":"text"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testNotExists()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('bool', (new ObjectNode())
            ->add('must_not', (new ObjectNode())
                ->add('exists', (new ObjectNode())
                    ->add('field', new ScalarNode('text'))
                )
            )
        )
    );
EOD;

        $json = '{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testPrefix()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('prefix', (new ObjectNode())
            ->add('title', new ScalarNode('elastic'))
        )
    );
EOD;

        $json = '{"query":{"prefix":{"title":"elastic"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testWildcard()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('wildcard', (new ObjectNode())
            ->add('title', new ScalarNode('ela*c'))
        )
    );
EOD;

        $json = '{"query":{"wildcard":{"title":"ela*c"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testRegexp()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('regexp', (new ObjectNode())
            ->add('title', new ScalarNode('search$'))
        )
    );
EOD;

        $json = '{"query":{"regexp":{"title":"search$"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testFuzzy()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('fuzzy', (new ObjectNode())
            ->add('title', (new ObjectNode())
                ->add('value', new ScalarNode('sea'))
                ->add('fuzziness', new ScalarNode(2))
            )
        )
    );
EOD;

        $json = '{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testType()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('type', (new ObjectNode())
            ->add('value', new ScalarNode('product'))
        )
    );
EOD;

        $json = '{"query":{"type":{"value":"product"}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testIds()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('ids', (new ObjectNode())
            ->add('type', new ScalarNode('product'))
            ->add('values', (new ArrayNode())
                ->add(new ScalarNode(1))
                ->add(new ScalarNode(2))
            )
        )
    );
EOD;

        $json = '{"query":{"ids":{"type":"product","values":[1,2]}}}';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testWithArrayNode()
    {
        $expect = <<<'EOD'
$node = (new ArrayNode())
    ->add(new ScalarNode(1))
    ->add(new ScalarNode(2));
EOD;

        $json = '[1,2]';

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testComplex()
    {
        $expect = <<<'EOD'
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('bool', (new ObjectNode())
            ->add('must', (new ObjectNode())
                ->add('term', (new ObjectNode())
                    ->add('user', new ScalarNode('kimchy'))
                )
            )
            ->add('filter', (new ObjectNode())
                ->add('term', (new ObjectNode())
                    ->add('tag', new ScalarNode('tech'))
                )
            )
            ->add('must_not', (new ObjectNode())
                ->add('range', (new ObjectNode())
                    ->add('age', (new ObjectNode())
                        ->add('from', new ScalarNode(10))
                        ->add('to', new ScalarNode(20))
                    )
                )
            )
            ->add('should', (new ArrayNode())
                ->add((new ObjectNode())
                    ->add('term', (new ObjectNode())
                        ->add('tag', new ScalarNode('wow'))
                    )
                )
                ->add((new ObjectNode())
                    ->add('term', (new ObjectNode())
                        ->add('tag', new ScalarNode('elasticsearch'))
                    )
                )
            )
            ->add('minimum_should_match', new ScalarNode(1))
            ->add('boost', new ScalarNode(1.2))
            ->add('enabled', new ScalarNode(true))
            ->add('relation', new ScalarNode(null))
            ->add('array', (new ArrayNode())
                ->add((new ArrayNode())
                    ->add((new ObjectNode())
                        ->add('term', (new ObjectNode())
                            ->add('tag', new ScalarNode('wow'))
                        )
                    )
                )
            )
        )
    );
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

        $generator = new NodeGenerator(new PhpGenerator());

        self::assertSame($expect, $generator->generateByJson($json));
    }

    public function testWithInvalidJson()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Message: Syntax error, query: {"query":{"ids":{"type":"product","values":[1,2]}}');

        $json = '{"query":{"ids":{"type":"product","values":[1,2]}}';

        $generator = new NodeGenerator(new PhpGenerator());
        $generator->generateByJson($json);
    }
}

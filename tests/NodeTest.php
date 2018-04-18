<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\AbstractNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\BoolNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\FloatNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\IntNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\NullNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode
 * @covers \Saxulum\ElasticSearchQueryBuilder\Node\StringNode
 */
class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('match_all', ObjectNode::create(true))
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"match_all":{}}}', $node->json());
    }

    public function testMatchAllWithoutAllowSerializeEmpty()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('match_all', ObjectNode::create())
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('', $node->json());
    }

    public function testMatch()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('match', ObjectNode::create()
                    ->add('title', StringNode::create('elasticsearch'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"match":{"title":"elasticsearch"}}}', $node->json());
    }

    public function testRange()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('range', ObjectNode::create()
                    ->add('elements', ObjectNode::create()
                        ->add('gte', IntNode::create(10))
                        ->add('lte', IntNode::create(20))
                    )
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"range":{"elements":{"gte":10,"lte":20}}}}', $node->json());
    }

    public function testExists()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('exists', ObjectNode::create()
                    ->add('field', StringNode::create('text'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"exists":{"field":"text"}}}', $node->json());
    }

    public function testNotExists()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('bool', ObjectNode::create()
                    ->add('must_not', ArrayNode::create()
                        ->add(ObjectNode::create()
                            ->add('exists', ObjectNode::create()
                                ->add('field', StringNode::create('text'))
                            )
                        )
                    )
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame(
            '{"query":{"bool":{"must_not":[{"exists":{"field":"text"}}]}}}',
            $node->json()
        );
    }

    public function testPrefix()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('prefix', ObjectNode::create()
                    ->add('title', StringNode::create('elastic'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"prefix":{"title":"elastic"}}}', $node->json());
    }

    public function testWildcard()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('wildcard', ObjectNode::create()
                    ->add('title', StringNode::create('ela*c'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $node->json());
    }

    public function testRegexp()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('regexp', ObjectNode::create()
                    ->add('title', StringNode::create('search$'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"regexp":{"title":"search$"}}}', $node->json());
    }

    public function testFuzzy()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('fuzzy', ObjectNode::create()
                    ->add('title', ObjectNode::create()
                        ->add('value', StringNode::create('sea'))
                        ->add('fuzziness', IntNode::create(2))
                    )
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}', $node->json());
    }

    public function testType()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('type', ObjectNode::create()
                    ->add('value', StringNode::create('product'))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"type":{"value":"product"}}}', $node->json());
    }

    public function testIds()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('ids', ObjectNode::create()
                    ->add('type', StringNode::create('product'))
                    ->add('values', ArrayNode::create()
                        ->add(IntNode::create(1))
                        ->add(IntNode::create(2))
                    )
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"ids":{"type":"product","values":[1,2]}}}', $node->json());
    }

    public function testBoolTerm()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('term', ObjectNode::create()
                    ->add('is_published', BoolNode::create(true))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"term":{"is_published":true}}}', $node->json());
    }

    public function testNullNode()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('term', ObjectNode::create()
                    ->add('field', NullNode::create())
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('{"query":{"term":{"field":null}}}', $node->json());
    }

    public function testComplex()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('bool', ObjectNode::create()
                    ->add('must', ObjectNode::create()
                        ->add('term', ObjectNode::create()
                            ->add('user', StringNode::create('kimchy'))
                        )
                    )
                    ->add('filter', ObjectNode::create()
                        ->add('term', ObjectNode::create()
                            ->add('tag', StringNode::create('tech'))
                        )
                    )
                    ->add('must_not', ObjectNode::create()
                        ->add('range', ObjectNode::create()
                            ->add('age', ObjectNode::create()
                                ->add('from', IntNode::create(10))
                                ->add('to', IntNode::create(20))
                            )
                        )
                    )
                    ->add('should', ArrayNode::create()
                        ->add(ObjectNode::create()
                            ->add('term', ObjectNode::create()
                                ->add('tag', StringNode::create('wow'))
                            )
                        )
                        ->add(ObjectNode::create()
                            ->add('term', ObjectNode::create()
                                ->add('tag', StringNode::create('elasticsearch'))
                            )
                        )
                    )
                    ->add('minimum_should_match', IntNode::create(1))
                    ->add('boost', FloatNode::create(1.1))
                )
            );

        $error = error_get_last();

        self::assertNull($error);

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

        self::assertSame($expected, $node->json(true));
    }

    public function testEmptyQuery()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('bool', ObjectNode::create()
                    ->add('must', ArrayNode::create()
                        ->add(ObjectNode::create()
                            ->add('terms', ObjectNode::create()
                                ->add('field', ArrayNode::create()
                                    ->add(StringNode::create())
                                )
                            )
                        )
                    )
                )
            );

        $error = error_get_last();

        self::assertNull($error);

        self::assertSame('', $node->json());
    }

    public function testArrayNodeWithAllowEmptySerializeChild()
    {
        $node = ArrayNode::create()
            ->add(StringNode::create(null, true));

        self::assertSame([null], $node->serialize());
    }

    public function testClone()
    {
        $stringNode = StringNode::create('value');

        $arrayNode = ArrayNode::create()
            ->add($stringNode)
        ;

        $objectNode = ObjectNode::create()
            ->add('key', $arrayNode);

        $objectNodeClone1 = clone $objectNode;
        $objectNodeClone2 = clone $objectNode;

        self::assertNotSame($objectNode, $objectNodeClone1);
        self::assertNotSame($objectNode, $objectNodeClone2);

        $objectNodeReflection = new \ReflectionProperty(ObjectNode::class, 'children');
        $objectNodeReflection->setAccessible(true);

        $arrayNodeClone1 = $objectNodeReflection->getValue($objectNodeClone1)['key'];
        $arrayNodeClone2 = $objectNodeReflection->getValue($objectNodeClone2)['key'];

        self::assertNotSame($arrayNode, $arrayNodeClone1);
        self::assertNotSame($arrayNode, $arrayNodeClone2);

        $arrayNodeReflection = new \ReflectionProperty(ArrayNode::class, 'children');
        $arrayNodeReflection->setAccessible(true);

        $stringNodeClone1 = $arrayNodeReflection->getValue($arrayNodeClone1)[0];
        $stringNodeClone2 = $arrayNodeReflection->getValue($arrayNodeClone2)[0];

        self::assertNotSame($stringNode, $stringNodeClone1);
        self::assertNotSame($stringNode, $stringNodeClone2);
    }

    public function testDuplicateKeyOnObjectNode()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('There is already a node with key name!');

        ObjectNode::create()
            ->add('name', StringNode::create())
            ->add('name', StringNode::create());
    }

    public function testAssignSameNodeTwice()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Node already got a parent!');

        $childNode = StringNode::create();

        ArrayNode::create()
            ->add($childNode)
            ->add($childNode);
    }

    public function testGetParentNode()
    {
        $childNode = StringNode::create();

        $parentNode = ArrayNode::create()->add($childNode);

        self::assertSame($parentNode, $childNode->getParent());
    }
}

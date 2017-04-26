<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

/**
 * @coversNothing
 */
class NodeTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchAll()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('match_all', ObjectNode::create(true))
            );

        self::assertSame('{"query":{"match_all":{}}}', $node->json());
    }

    public function testMatchAllWithoutAllowSerializeEmpty()
    {
        $node = ObjectNode::create()
            ->add('query', ObjectNode::create()
                ->add('match_all', ObjectNode::create())
            );

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

        self::assertSame('{"query":{"wildcard":{"title":"ela*c"}}}', $node->json());
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

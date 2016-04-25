<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\CallbackNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class CallbackNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testSerializeWithNull()
    {
        $node = new CallbackNode(function (CallbackNode $node) {
            self::assertInstanceOf(CallbackNode::class, $node);
            return null;
        });

        self::assertSame(null, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithArrayNode()
    {
        $node = new CallbackNode(function () {
            $arrayNode = new ArrayNode();
            for ($i = 0; $i < 5; $i++) {
                $arrayNode->add(new ScalarNode($i));
            }

            return $arrayNode->serialize();
        });

        self::assertSame([0, 1, 2, 3, 4], $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithArrayNodeDefault()
    {
        $node = new CallbackNode(function () {
            $arrayNode = new ArrayNode();

            return $arrayNode->serialize();
        });

        self::assertSame(null, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithArrayNodeDefaultAllowDefault()
    {
        $node = new CallbackNode(function () {
            $arrayNode = new ArrayNode();

            return $arrayNode->serialize();
        }, true);

        self::assertSame(null, $node->serialize());
    }
}

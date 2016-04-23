<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ArrayNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testGetName()
    {
        $node = new ArrayNode('name');

        self::assertSame('name', $node->getName());
    }

    /**
     * @return void
     */
    public function testSerializeWithoutChildren()
    {
        $node = new ArrayNode('name');

        self::assertSame(['name' => []], $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithScalarChild()
    {
        $node = (new ArrayNode('name1'))
            ->addChild(new ScalarNode('name11', 'value11'))
            ->addChild(new ScalarNode('name12', 'value12'))
        ;

        self::assertSame(
            [
                'name1' => [
                    ['name11' => 'value11'],
                    ['name12' => 'value12']
                ]
            ],
            $node->serialize()
        );
    }

    /**
     * @return void
     */
    public function testSerializeWithObjectChild()
    {
        $node = (new ArrayNode('name1'))
            ->addChild(
                (new ObjectNode('name11'))
                    ->addChild(new ScalarNode('name111', 'value111'))
                    ->addChild(new ScalarNode('name112', 'value112'))
            )
            ->addChild(
                (new ObjectNode('name12'))
                    ->addChild(new ScalarNode('name121', 'value121'))
                    ->addChild(new ScalarNode('name122', 'value122'))
            )
        ;

        self::assertSame(
            [
                'name1' => [
                    [
                        'name11' => [
                            'name111' => 'value111',
                            'name112' => 'value112'
                        ]
                    ],
                    [
                        'name12' => [
                            'name121' => 'value121',
                            'name122' => 'value122'
                        ]
                    ]
                ]
            ],
            $node->serialize()
        );
    }

    /**
     * @return void
     */
    public function testSerializeWithArrayChild()
    {
        $node = (new ArrayNode('name1'))
            ->addChild(
                (new ArrayNode('name11'))
                    ->addChild(new ScalarNode('name111', 'value111'))
                    ->addChild(new ScalarNode('name112', 'value112'))
            )
            ->addChild(
                (new ArrayNode('name12'))
                    ->addChild(new ScalarNode('name121', 'value121'))
                    ->addChild(new ScalarNode('name122', 'value122'))
            )
        ;

        self::assertSame(
            [
                'name1' => [
                    [
                        'name11' => [
                            ['name111' => 'value111'],
                            ['name112' => 'value112']
                        ]
                    ],
                    [
                        'name12' => [
                            ['name121' => 'value121'],
                            ['name122' => 'value122']
                        ]
                    ]
                ]
            ],
            $node->serialize()
        );
    }
}

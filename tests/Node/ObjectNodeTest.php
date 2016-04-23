<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ObjectNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testGetName()
    {
        $node = new ObjectNode('name');
        
        self::assertSame('name', $node->getName());
    }

    /**
     * @return void
     */
    public function testSerializeWithoutChildren()
    {
        $node = new ObjectNode('name');

        self::assertInstanceOf(\stdClass::class, $node->serialize());

        $serialized = new \stdClass();
        $serialized->name = new \stdClass();

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithScalarChild()
    {
        $node = (new ObjectNode('name1'))
            ->add(new ScalarNode('name11', 'value11'))
            ->add(new ScalarNode('name12', 'value12'))
        ;


        $serialized = new \stdClass();
        $serialized->name1 = new \stdClass();
        $serialized->name1->name11 = 'value11';
        $serialized->name1->name12 = 'value12';

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithObjectChild()
    {
        $node = (new ObjectNode('name1'))
            ->add(
                (new ObjectNode('name11'))
                    ->add(new ScalarNode('name111', 'value111'))
                    ->add(new ScalarNode('name112', 'value112'))
            )
            ->add(
                (new ObjectNode('name12'))
                    ->add(new ScalarNode('name121', 'value121'))
                    ->add(new ScalarNode('name122', 'value122'))
            )
        ;

        $serialized = new \stdClass();
        $serialized->name1 = new \stdClass();
        $serialized->name1->name11 = new \stdClass();
        $serialized->name1->name11->name111 = 'value111';
        $serialized->name1->name11->name112 = 'value112';
        $serialized->name1->name12 = new \stdClass();
        $serialized->name1->name12->name121 = 'value121';
        $serialized->name1->name12->name122 = 'value122';

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithArrayChild()
    {
        $node = (new ObjectNode('name1'))
            ->add(
                (new ArrayNode('name11'))
                    ->add(
                        (new ObjectNode('name111'))
                            ->add(new ScalarNode('name1111', 'value1111'))
                            ->add(new ScalarNode('name1112', 'value1112'))
                    )
                    ->add(
                        (new ObjectNode('name112'))
                            ->add(new ScalarNode('name1121', 'value1121'))
                            ->add(new ScalarNode('name1122', 'value1122'))
                    )
            )
        ;

        $serialized = new \stdClass();
        $serialized->name1 = new \stdClass();
        $serialized->name1->name11 = [];
        $serialized->name1->name11[0] = new \stdClass();
        $serialized->name1->name11[0]->name111 = new \stdClass();
        $serialized->name1->name11[0]->name111->name1111 = 'value1111';
        $serialized->name1->name11[0]->name111->name1112 = 'value1112';

        $serialized->name1->name11[1] = new \stdClass();
        $serialized->name1->name11[1]->name112 = new \stdClass();
        $serialized->name1->name11[1]->name112->name1121 = 'value1121';
        $serialized->name1->name11[1]->name112->name1122 = 'value1122';

        self::assertEquals($serialized, $node->serialize());
    }
}

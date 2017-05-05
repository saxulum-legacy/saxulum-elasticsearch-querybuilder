<?php

declare(strict_types=1);

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Converter;

use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter
 */
class ScalarToNodeConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertBool()
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(BoolNode::class, $valueConverter->convert(true));
    }

    public function testConvertFloat()
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(FloatNode::class, $valueConverter->convert(1.234));
    }

    public function testConvertInt()
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(IntNode::class, $valueConverter->convert(1));
    }

    public function testConvertNull()
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(NullNode::class, $valueConverter->convert(null));
    }

    public function testConvertString()
    {
        $valueConverter = new ScalarToNodeConverter();

        self::assertInstanceOf(StringNode::class, $valueConverter->convert('string'));
    }

    public function testConvertWithUnsupportedValueExpectException()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Type DateTime is not supported, at path dates[0]');

        $valueConverter = new ScalarToNodeConverter();
        $valueConverter->convert(new \DateTime(), 'dates[0]');
    }
}

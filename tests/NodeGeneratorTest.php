<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use PhpParser\PrettyPrinter\Standard as PhpGenerator;
use Saxulum\ElasticSearchQueryBuilder\NodeGenerator;
use Saxulum\ElasticSearchQueryBuilder\QueryBuilderGenerator;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\NodeGenerator
 */
class NodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testBla()
    {
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
            "boost": 1
        }
    }
}
EOD;

        $generator = new NodeGenerator(new PhpGenerator());
        echo $generator->generateByJson($json);
    }
}

<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder;

use Saxulum\ElasticSearchQueryBuilder\Generator;

/**
 * @covers \Saxulum\ElasticSearchQueryBuilder\Generator
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
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

        $generator = new Generator();
        $generator->generateByJson($json);



    }
}

# Node Generator

## Code

```php
use PhpParser\PrettyPrinter\Standard as PhpGenerator;
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

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
```

## Output

```php
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
            ->add('boost', new ScalarNode(1))
        )
    );
```

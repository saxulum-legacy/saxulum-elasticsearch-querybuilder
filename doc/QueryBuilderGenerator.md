# Query Builder Generator

## Code

```{.php}
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

$generator = new QueryBuilderGenerator(new PhpGenerator());

echo $generator->generateByJson($json);
```

## Output

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('bool', $qb->objectNode())
            ->addToObjectNode('must', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('user', $qb->scalarNode('kimchy'))
                ->end()
            ->end()
            ->addToObjectNode('filter', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('tag', $qb->scalarNode('tech'))
                ->end()
            ->end()
            ->addToObjectNode('must_not', $qb->objectNode())
                ->addToObjectNode('range', $qb->objectNode())
                    ->addToObjectNode('age', $qb->objectNode())
                        ->addToObjectNode('from', $qb->scalarNode(10))
                        ->addToObjectNode('to', $qb->scalarNode(20))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('should', $qb->arrayNode())
                ->addToArrayNode($qb->objectNode())
                    ->addToObjectNode('term', $qb->objectNode())
                        ->addToObjectNode('tag', $qb->scalarNode('wow'))
                    ->end()
                ->end()
                ->addToArrayNode($qb->objectNode())
                    ->addToObjectNode('term', $qb->objectNode())
                        ->addToObjectNode('tag', $qb->scalarNode('elasticsearch'))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('minimum_should_match', $qb->scalarNode(1))
            ->addToObjectNode('boost', $qb->scalarNode(1));
```

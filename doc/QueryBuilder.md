# QueryBuilder

## Match all

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('match_all', $qb->objectNode(), true)
;

echo $qb->json();
```

```{.json}
{"query":{"match_all":{}}}
```

## Match

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('match', $qb->objectNode())
            ->addToObjectNode('title', $qb->scalarNode('elasticsearch'))
;

echo $qb->json();
```

```{.json}
{"query":{"match":{"title":"elasticsearch"}}}
```

## Range

```{.php}
$qb = new QueryBuilder();
$qb
->addToObjectNode('query', $qb->objectNode())
    ->addToObjectNode('range', $qb->objectNode())
        ->addToObjectNode('elements', $qb->objectNode())
            ->addToObjectNode('gte', $qb->scalarNode(10))
            ->addToObjectNode('lte', $qb->scalarNode(20))
;

echo $qb->json();
```

```{.json}
{"query":{"range":{"elements":{"gte":10,"lte":20}}}}
```

## Exists

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('exists', $qb->objectNode())
            ->addToObjectNode('field', $qb->scalarNode('text'))
;

echo $qb->json();
```

```{.json}
{"query":{"exists":{"field":"text"}}}
```

## Not Exists

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('bool', $qb->objectNode())
            ->addToObjectNode('must_not', $qb->objectNode())
                ->addToObjectNode('exists', $qb->objectNode())
                    ->addToObjectNode('field', $qb->scalarNode('text'))
;

echo $qb->json();
```

```{.json}
{"query":{"bool":{"must_not":{"exists":{"field":"text"}}}}}
```

## Prefix

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('prefix', $qb->objectNode())
            ->addToObjectNode('title', $qb->scalarNode('elastic'))
;

echo $qb->json();
```

```{.json}
{"query":{"prefix":{"title":"elastic"}}}
```

## Wildcard

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('wildcard', $qb->objectNode())
            ->addToObjectNode('title', $qb->scalarNode('ela*c'))
;

echo $qb->json();
```

```{.json}
{"query":{"wildcard":{"title":"ela*c"}}}
```

## Regexp

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('regexp', $qb->objectNode())
            ->addToObjectNode('title', $qb->scalarNode('search$'))
;

echo $qb->json();
```

```{.json}
{"query":{"regexp":{"title":"search$"}}}
```

## Fuzzy

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('fuzzy', $qb->objectNode())
            ->addToObjectNode('title', $qb->objectNode())
                ->addToObjectNode('value', $qb->scalarNode('sea'))
                ->addToObjectNode('fuzziness', $qb->scalarNode(2))
;

echo $qb->json();
```

```{.json}
{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}
```

## Type

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('type', $qb->objectNode())
            ->addToObjectNode('value', $qb->scalarNode('product'))
;

echo $qb->json();
```

```{.json}
{"query":{"type":{"value":"product"}}}
```

## Ids

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('ids', $qb->objectNode())
            ->addToObjectNode('type', $qb->scalarNode('product'))
            ->addToObjectNode('values', $qb->arrayNode())
                ->addToArrayNode($qb->scalarNode(1))
                ->addToArrayNode($qb->scalarNode(2))
;

echo $qb->json();
```

```{.json}
{"query":{"ids":{"type":"product","values":[1,2]}}}
```

## Complex sample

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
            ->addToObjectNode('boost', $qb->scalarNode(1))
;

echo $qb->json(true);
```

```{.json}
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
```

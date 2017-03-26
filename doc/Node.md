# Node

## Match all

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match_all', new ObjectNode(), true)
    );

echo json_encode($node->serialize());
```

```json
{"query":{"match_all":{}}}
```

## Match

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match', (new ObjectNode())
            ->add('title', new ScalarNode('elasticsearch'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"match":{"title":"elasticsearch"}}}
```

## Range

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('range', (new ObjectNode())
            ->add('elements', (new ObjectNode())
                ->add('gte', new ScalarNode(10))
                ->add('lte', new ScalarNode(20))
            )
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"range":{"elements":{"gte":10,"lte":20}}}}
```

## Exists

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('exists', (new ObjectNode())
            ->add('field', new ScalarNode('text'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"exists":{"field":"text"}}}
```

## Not Exists

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('bool', (new ObjectNode())
            ->add('must_not', (new ArrayNode())
                ->add((new ObjectNode())
                    ->add('exists', (new ObjectNode())
                        ->add('field', new ScalarNode('text'))
                    )
                )
            )
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"bool":{"must_not":[{"exists":{"field":"text"}}]}}}
```

## Prefix

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('prefix', (new ObjectNode())
            ->add('title', new ScalarNode('elastic'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"prefix":{"title":"elastic"}}}
```

## Wildcard

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('wildcard', (new ObjectNode())
            ->add('title', new ScalarNode('ela*c'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"wildcard":{"title":"ela*c"}}}
```

## Regexp

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('regexp', (new ObjectNode())
            ->add('title', new ScalarNode('search$'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"regexp":{"title":"search$"}}}
```

## Fuzzy

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('fuzzy', (new ObjectNode())
            ->add('title', (new ObjectNode())
                ->add('value', new ScalarNode('sea'))
                ->add('fuzziness', new ScalarNode(2))
            )
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}
```

## Type

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('type', (new ObjectNode())
            ->add('value', new ScalarNode('product'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"type":{"value":"product"}}}
```

## Ids

```php
$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('ids', (new ObjectNode())
            ->add('type', (new ScalarNode('product')))
            ->add('values', (new ArrayNode())
                ->add(new ScalarNode(1))
                ->add(new ScalarNode(2))
            )
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"ids":{"type":"product","values":[1,2]}}}
```

## Complex sample

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


echo json_encode($node->serialize(), JSON_PRETTY_PRINT);
```

```json
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

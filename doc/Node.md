# Node

## Match all

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match_all', new ObjectNode(true))
    );

echo json_encode($node->serialize());
```

```json
{"query":{"match_all":{}}}
```

## Match

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('match', (new ObjectNode())
            ->add('title', new StringNode('elasticsearch'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"match":{"title":"elasticsearch"}}}
```

## Range

```php
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('range', (new ObjectNode())
            ->add('elements', (new ObjectNode())
                ->add('gte', new IntNode(10))
                ->add('lte', new IntNode(20))
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
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('exists', (new ObjectNode())
            ->add('field', new StringNode('text'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"exists":{"field":"text"}}}
```

## Not Exists

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('bool', (new ObjectNode())
            ->add('must_not', (new ArrayNode())
                ->add((new ObjectNode())
                    ->add('exists', (new ObjectNode())
                        ->add('field', new StringNode('text'))
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
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('prefix', (new ObjectNode())
            ->add('title', new StringNode('elastic'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"prefix":{"title":"elastic"}}}
```

## Wildcard

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('wildcard', (new ObjectNode())
            ->add('title', new StringNode('ela*c'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"wildcard":{"title":"ela*c"}}}
```

## Regexp

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('regexp', (new ObjectNode())
            ->add('title', new StringNode('search$'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"regexp":{"title":"search$"}}}
```

## Fuzzy

```php
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('fuzzy', (new ObjectNode())
            ->add('title', (new ObjectNode())
                ->add('value', new StringNode('sea'))
                ->add('fuzziness', new IntNode(2))
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
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('type', (new ObjectNode())
            ->add('value', new StringNode('product'))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"type":{"value":"product"}}}
```

## Ids

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('ids', (new ObjectNode())
            ->add('type', (new StringNode('product')))
            ->add('values', (new ArrayNode())
                ->add(new IntNode(1))
                ->add(new IntNode(2))
            )
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"ids":{"type":"product","values":[1,2]}}}
```

## BoolTerm

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('term', (new ObjectNode())
            ->add('is_published', (new BoolNode(true)))
        )
    );

echo json_encode($node->serialize());
```

```json
{"query":{"term":{"is_published":true}}}
```

## Complex sample

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$node = (new ObjectNode())
    ->add('query', (new ObjectNode())
        ->add('bool', (new ObjectNode())
            ->add('must', (new ObjectNode())
                ->add('term', (new ObjectNode())
                    ->add('user', new StringNode('kimchy'))
                )
            )
            ->add('filter', (new ObjectNode())
                ->add('term', (new ObjectNode())
                    ->add('tag', new StringNode('tech'))
                )
            )
            ->add('must_not', (new ObjectNode())
                ->add('range', (new ObjectNode())
                    ->add('age', (new ObjectNode())
                        ->add('from', new IntNode(10))
                        ->add('to', new IntNode(20))
                    )
                )
            )
            ->add('should', (new ArrayNode())
                ->add((new ObjectNode())
                    ->add('term', (new ObjectNode())
                        ->add('tag', new StringNode('wow'))
                    )
                )
                ->add((new ObjectNode())
                    ->add('term', (new ObjectNode())
                        ->add('tag', new StringNode('elasticsearch'))
                    )
                )
            )
            ->add('minimum_should_match', new IntNode(1))
            ->add('boost', new FloatNode(1.1))
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
            "boost": 1.1
        }
    }
}
```

# Node

## Match all

```php
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('match_all', ObjectNode::create(true))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('match', ObjectNode::create()
            ->add('title', StringNode::create('elasticsearch'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('range', ObjectNode::create()
            ->add('elements', ObjectNode::create()
                ->add('gte', IntNode::create(10))
                ->add('lte', IntNode::create(20))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('exists', ObjectNode::create()
            ->add('field', StringNode::create('text'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('bool', ObjectNode::create()
            ->add('must_not', ArrayNode::create()
                ->add(ObjectNode::create()
                    ->add('exists', ObjectNode::create()
                        ->add('field', StringNode::create('text'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('prefix', ObjectNode::create()
            ->add('title', StringNode::create('elastic'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('wildcard', ObjectNode::create()
            ->add('title', StringNode::create('ela*c'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('regexp', ObjectNode::create()
            ->add('title', StringNode::create('search$'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('fuzzy', ObjectNode::create()
            ->add('title', ObjectNode::create()
                ->add('value', StringNode::create('sea'))
                ->add('fuzziness', IntNode::create(2))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('type', ObjectNode::create()
            ->add('value', StringNode::create('product'))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('ids', ObjectNode::create()
            ->add('type', (StringNode::create('product')))
            ->add('values', ArrayNode::create()
                ->add(IntNode::create(1))
                ->add(IntNode::create(2))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('term', ObjectNode::create()
            ->add('is_published', (BoolNode::create(true)))
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

$node = ObjectNode::create()
    ->add('query', ObjectNode::create()
        ->add('bool', ObjectNode::create()
            ->add('must', ObjectNode::create()
                ->add('term', ObjectNode::create()
                    ->add('user', StringNode::create('kimchy'))
                )
            )
            ->add('filter', ObjectNode::create()
                ->add('term', ObjectNode::create()
                    ->add('tag', StringNode::create('tech'))
                )
            )
            ->add('must_not', ObjectNode::create()
                ->add('range', ObjectNode::create()
                    ->add('age', ObjectNode::create()
                        ->add('from', IntNode::create(10))
                        ->add('to', IntNode::create(20))
                    )
                )
            )
            ->add('should', ArrayNode::create()
                ->add(ObjectNode::create()
                    ->add('term', ObjectNode::create()
                        ->add('tag', StringNode::create('wow'))
                    )
                )
                ->add(ObjectNode::create()
                    ->add('term', ObjectNode::create()
                        ->add('tag', StringNode::create('elasticsearch'))
                    )
                )
            )
            ->add('minimum_should_match', IntNode::create(1))
            ->add('boost', FloatNode::create(1.1))
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

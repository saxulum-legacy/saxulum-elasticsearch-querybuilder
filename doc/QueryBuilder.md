# QueryBuilder

## Match all

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('match_all', $qb->objectNode(), true)
;

echo $qb->json();
```

```json
{"query":{"match_all":{}}}
```

## Match

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('match', $qb->objectNode())
            ->add('title', $qb->stringNode('elasticsearch'))
;

echo $qb->json();
```

```json
{"query":{"match":{"title":"elasticsearch"}}}
```

## Range

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
->add('query', $qb->objectNode())
    ->add('range', $qb->objectNode())
        ->add('elements', $qb->objectNode())
            ->add('gte', $qb->intNode(10))
            ->add('lte', $qb->intNode(20))
;

echo $qb->json();
```

```json
{"query":{"range":{"elements":{"gte":10,"lte":20}}}}
```

## Exists

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('exists', $qb->objectNode())
            ->add('field', $qb->stringNode('text'))
;

echo $qb->json();
```

```json
{"query":{"exists":{"field":"text"}}}
```

## Not Exists

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('bool', $qb->objectNode())
            ->add('must_not', $qb->arrayNode())
                ->add($qb->objectNode())
                    ->add('exists', $qb->objectNode())
                        ->add('field', $qb->stringNode('text'))
;

echo $qb->json();
```

```json
{"query":{"bool":{"must_not":[{"exists":{"field":"text"}}]}}}
```

## Prefix

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('prefix', $qb->objectNode())
            ->add('title', $qb->stringNode('elastic'))
;

echo $qb->json();
```

```json
{"query":{"prefix":{"title":"elastic"}}}
```

## Wildcard

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('wildcard', $qb->objectNode())
            ->add('title', $qb->stringNode('ela*c'))
;

echo $qb->json();
```

```json
{"query":{"wildcard":{"title":"ela*c"}}}
```

## Regexp

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('regexp', $qb->objectNode())
            ->add('title', $qb->stringNode('search$'))
;

echo $qb->json();
```

```json
{"query":{"regexp":{"title":"search$"}}}
```

## Fuzzy

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('fuzzy', $qb->objectNode())
            ->add('title', $qb->objectNode())
                ->add('value', $qb->stringNode('sea'))
                ->add('fuzziness', $qb->intNode(2))
;

echo $qb->json();
```

```json
{"query":{"fuzzy":{"title":{"value":"sea","fuzziness":2}}}}
```

## Type

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('type', $qb->objectNode())
            ->add('value', $qb->stringNode('product'))
;

echo $qb->json();
```

```json
{"query":{"type":{"value":"product"}}}
```

## Ids

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('ids', $qb->objectNode())
            ->add('type', $qb->stringNode('product'))
            ->add('values', $qb->arrayNode())
                ->add($qb->intNode(1))
                ->add($qb->intNode(2))
;

echo $qb->json();
```

```json
{"query":{"ids":{"type":"product","values":[1,2]}}}
```

## Bool Term

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('term', $qb->objectNode())
            ->add('is_published', $qb->boolNode(true))
;

echo $qb->json();
```

```json
{"query":{"term":{"is_published":true}}}
```

## Complex sample

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->add('query', $qb->objectNode())
        ->add('bool', $qb->objectNode())
            ->add('must', $qb->objectNode())
                ->add('term', $qb->objectNode())
                    ->add('user', $qb->stringNode('kimchy'))
                ->end()
            ->end()
            ->add('filter', $qb->objectNode())
                ->add('term', $qb->objectNode())
                    ->add('tag', $qb->stringNode('tech'))
                ->end()
            ->end()
            ->add('must_not', $qb->objectNode())
                ->add('range', $qb->objectNode())
                    ->add('age', $qb->objectNode())
                        ->add('from', $qb->intNode(10))
                        ->add('to', $qb->intNode(20))
                    ->end()
                ->end()
            ->end()
            ->add('should', $qb->arrayNode())
                ->add($qb->objectNode())
                    ->add('term', $qb->objectNode())
                        ->add('tag', $qb->stringNode('wow'))
                    ->end()
                ->end()
                ->add($qb->objectNode())
                    ->add('term', $qb->objectNode())
                        ->add('tag', $qb->stringNode('elasticsearch'))
                    ->end()
                ->end()
            ->end()
            ->add('minimum_should_match', $qb->intNode(1))
            ->add('boost', $qb->floatNode(1.1))
;

echo $qb->json(true);
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

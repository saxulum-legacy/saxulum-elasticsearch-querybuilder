# saxulum-elasticsearch-querybuilder

[![Build Status](https://api.travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder.png?branch=master)](https://travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder)
[![Total Downloads](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/downloads.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Latest Stable Version](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/v/stable.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)

## Features

 * A simple to use, flexible query builder for elastic search.

## Requirements

 * php: ~7.0

## Suggests

 * saxulum/saxulum-elasticsearch-querybuilder-generator: ~1.0@dev

## Installation

Through [Composer](http://getcomposer.org) as [saxulum/saxulum-elasticsearch-querybuilder][1].

## Usage

**Important**: By default empty nodes get not serialized, which means empty arrayNode (no elemements), empty
objectNode (no keys) and empty scalar Nodes (null). This works recursive, which means theoretically a complex
query builder can lead into an empty string as json. Check the `Important methods` to get more information
to prevent this if needed.

### QueryBuilder

```php
use Saxulum\ElasticSearchQueryBuilder\QueryBuilder;

$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', $qb->objectNode())
        ->addToObjectNode('bool', $qb->objectNode())
            ->addToObjectNode('must', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('user', $qb->stringNode('kimchy'))
                ->end()
            ->end()
            ->addToObjectNode('filter', $qb->objectNode())
                ->addToObjectNode('term', $qb->objectNode())
                    ->addToObjectNode('tag', $qb->stringNode('tech'))
                ->end()
            ->end()
            ->addToObjectNode('must_not', $qb->objectNode())
                ->addToObjectNode('range', $qb->objectNode())
                    ->addToObjectNode('age', $qb->objectNode())
                        ->addToObjectNode('from', $qb->intNode(10))
                        ->addToObjectNode('to', $qb->intNode(20))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('should', $qb->arrayNode())
                ->addToArrayNode($qb->objectNode())
                    ->addToObjectNode('term', $qb->objectNode())
                        ->addToObjectNode('tag', $qb->stringNode('wow'))
                    ->end()
                ->end()
                ->addToArrayNode($qb->objectNode())
                    ->addToObjectNode('term', $qb->objectNode())
                        ->addToObjectNode('tag', $qb->stringNode('elasticsearch'))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('minimum_should_match', $qb->intNode(1))
            ->addToObjectNode('boost', $qb->floatNode(1.1))
;

echo $qb->json(true);
```

#### Important methods

##### addToArrayNode


```php
/**
 * @param AbstractNode $node
 * @param bool         $allowDefault
 *
 * @return QueryBuilderInterface
 *
 * @throws \Exception
 */
public function addToArrayNode(
    AbstractNode $node,
    bool $allowDefault = false
): QueryBuilderInterface;
```

##### addToObjectNode


```php
/**
 * @param string       $key
 * @param AbstractNode $node
 * @param bool         $allowDefault
 *
 * @return QueryBuilderInterface
 *
 * @throws \Exception
 */
public function addToObjectNode(
    string $key,
    AbstractNode $node,
    bool $allowDefault = false
): QueryBuilderInterface;
```

### Other samples

 * [Queries with QueryBuilder][2]
 * [Queries with Node][3]


[1]: https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder
[2]: doc/QueryBuilder.md
[3]: doc/Node.md

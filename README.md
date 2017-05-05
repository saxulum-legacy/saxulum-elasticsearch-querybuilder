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

 * saxulum/saxulum-elasticsearch-querybuilder-generator: ~1.0

## Installation

Through [Composer](http://getcomposer.org) as [saxulum/saxulum-elasticsearch-querybuilder][1].

## Usage

**Important**: By default empty nodes get not serialized, which means empty arrayNode (no elemements), empty
objectNode (no keys) and empty scalar Nodes (null). This works recursive, which means theoretically a complex
query builder can lead into an empty string as json. Check the `allowSerializeEmpty` argument to prevent this if needed.

### QueryBuilder

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

### Other samples

 * [Queries with QueryBuilder][2]
 * [Queries with Node][3]

### Converter

 * [Convert Iteratable to Node][4]
 * [Convert Scalar to Node][5]

[1]: https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder
[2]: doc/QueryBuilder.md
[3]: doc/Node.md
[4]: doc/Converter/IteratableToNodeConverter.md
[5]: doc/Converter/ScalarToNodeConverter.md

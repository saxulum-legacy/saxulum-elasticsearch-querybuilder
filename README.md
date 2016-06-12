# saxulum-elasticsearch-querybuilder

[![Build Status](https://api.travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder.png?branch=master)](https://travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder)
[![Total Downloads](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/downloads.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Latest Stable Version](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/v/stable.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)

## Features

 * A simple to use, flexible query builder for elastic search.

## Usage

### Simple sample

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', new ObjectNode())
        ->addToObjectNode('match', new ObjectNode())
            ->addToObjectNode('title', new ScalarNode('elasticsearch'))
;

echo $qb->query()->json(true);
```

```{.json}
{
    "query": {
        "match": {
            "title": "elasticsearch"
        }
    }
}
```

### AllowDefault sample

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', new ObjectNode())
        ->addToObjectNode('match_all', new ObjectNode(), true)
;

echo $qb->query()->json(true);
```

```{.json}
{
    "query": {
        "match_all": {}
    }
}
```

### Complex sample

```{.php}
$qb = new QueryBuilder();
$qb
    ->addToObjectNode('query', new ObjectNode())
        ->addToObjectNode('bool', new ObjectNode())
            ->addToObjectNode('must', new ObjectNode())
                ->addToObjectNode('term', new ObjectNode())
                    ->addToObjectNode('user', new ScalarNode('kimchy'))
                ->end()
            ->end()
            ->addToObjectNode('filter', new ObjectNode())
                ->addToObjectNode('term', new ObjectNode())
                    ->addToObjectNode('tag', new ScalarNode('tech'))
                ->end()
            ->end()
            ->addToObjectNode('must_not', new ObjectNode())
                ->addToObjectNode('range', new ObjectNode())
                    ->addToObjectNode('age', new ObjectNode())
                        ->addToObjectNode('from', new ScalarNode(10))
                        ->addToObjectNode('to', new ScalarNode(20))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('should', new ArrayNode())
                ->addToArrayNode(new ObjectNode())
                    ->addToObjectNode('term', new ObjectNode())
                        ->addToObjectNode('tag', new ScalarNode('wow'))
                    ->end()
                ->end()
                ->addToArrayNode(new ObjectNode())
                    ->addToObjectNode('term', new ObjectNode())
                        ->addToObjectNode('tag', new ScalarNode('elasticsearch'))
                    ->end()
                ->end()
            ->end()
            ->addToObjectNode('minimum_should_match', new ScalarNode(1))
            ->addToObjectNode('boost', new ScalarNode(1))
;

echo $qb->query()->json(true);
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

## Requirements

 * php: ~5.5|~7.0

## Installation

Through [Composer](http://getcomposer.org) as [saxulum/saxulum-elasticsearch-querybuilder][1].

[1]: https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder

# saxulum-elasticsearch-querybuilder

[![Build Status](https://api.travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder.png?branch=master)](https://travis-ci.org/saxulum/saxulum-elasticsearch-querybuilder)
[![Total Downloads](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/downloads.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Latest Stable Version](https://poser.pugx.org/saxulum/saxulum-elasticsearch-querybuilder/v/stable.png)](https://packagist.org/packages/saxulum/saxulum-elasticsearch-querybuilder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/saxulum/saxulum-elasticsearch-querybuilder/?branch=master)

## Features

 * A simple to use, flexible query builder for elastic search.

## Usage

### Complex sample

```{.php}
$qb = new QueryBuilder();
$qb
    ->add($qb->obj()->key('query'))
        ->add($qb->obj()->key('bool'))
            ->add($qb->obj()->key('must'))
                ->add($qb->obj()->key('term'))
                    ->add($qb->sca('kimchy')->key('user'))
                ->end()
            ->end()
            ->add($qb->obj()->key('filter'))
                ->add($qb->obj()->key('term'))
                    ->add($qb->sca('tech')->key('tag'))
                ->end()
            ->end()
            ->add($qb->obj()->key('must_not'))
                ->add($qb->obj()->key('range'))
                    ->add($qb->obj()->key('age'))
                        ->add($qb->sca(10)->key('from'))
                        ->add($qb->sca(20)->key('to'))
                    ->end()
                ->end()
            ->end()
            ->add($qb->arr()->key('should'))
                ->add($qb->obj())
                    ->add($qb->obj()->key('term'))
                        ->add($qb->sca('wow')->key('tag'))
                    ->end()
                ->end()
                ->add($qb->obj())
                    ->add($qb->obj()->key('term'))
                        ->add($qb->sca('elasticsearch')->key('tag'))
                    ->end()
                ->end()
            ->end()
            ->add($qb->sca(1)->key('minimum_should_match'))
            ->add($qb->sca(1)->key('boost'))
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

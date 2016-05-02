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
            ->add($qb->o()->key('query'))
                ->add($qb->o()->key('bool'))
                    ->add($qb->o()->key('must'))
                        ->add($qb->o()->key('term'))
                            ->add($qb->s('kimchy')->key('user'))
                        ->end()
                    ->end()
                    ->add($qb->o()->key('filter'))
                        ->add($qb->o()->key('term'))
                            ->add($qb->s('tech')->key('tag'))
                        ->end()
                    ->end()
                    ->add($qb->o()->key('must_not'))
                        ->add($qb->o()->key('range'))
                            ->add($qb->o()->key('age'))
                                ->add($qb->s(10)->key('from'))
                                ->add($qb->s(20)->key('to'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->a()->key('should'))
                        ->add($qb->o())
                            ->add($qb->o()->key('term'))
                                ->add($qb->s('wow')->key('tag'))
                            ->end()
                        ->end()
                        ->add($qb->o())
                            ->add($qb->o()->key('term'))
                                ->add($qb->s('elasticsearch')->key('tag'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->s(1)->key('minimum_should_match'))
                    ->add($qb->s(1)->key('boost'))
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

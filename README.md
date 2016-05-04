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
            ->add($qb->o()->k('query'))
                ->add($qb->o()->k('bool'))
                    ->add($qb->o()->k('must'))
                        ->add($qb->o()->k('term'))
                            ->add($qb->s('kimchy')->k('user'))
                        ->end()
                    ->end()
                    ->add($qb->o()->k('filter'))
                        ->add($qb->o()->k('term'))
                            ->add($qb->s('tech')->k('tag'))
                        ->end()
                    ->end()
                    ->add($qb->o()->k('must_not'))
                        ->add($qb->o()->k('range'))
                            ->add($qb->o()->k('age'))
                                ->add($qb->s(10)->k('from'))
                                ->add($qb->s(20)->k('to'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->a()->k('should'))
                        ->add($qb->o())
                            ->add($qb->o()->k('term'))
                                ->add($qb->s('wow')->k('tag'))
                            ->end()
                        ->end()
                        ->add($qb->o())
                            ->add($qb->o()->k('term'))
                                ->add($qb->s('elasticsearch')->k('tag'))
                            ->end()
                        ->end()
                    ->end()
                    ->add($qb->s(1)->k('minimum_should_match'))
                    ->add($qb->s(1)->k('boost'))
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

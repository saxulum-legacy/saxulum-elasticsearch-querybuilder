# ScalarToNodeConverter

## Bool value

```php
<?php
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\BoolNode;

$valueConverter = new ScalarToNodeConverter();
$valueConverter->convert(true); // instanceof BoolNode::class
```

## Float value

```php
<?php
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\FloatNode;

$valueConverter = new ScalarToNodeConverter();
$valueConverter->convert(1.234); // instanceof FloatNode::class
```

## Int value

```php
<?php
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\IntNode;

$valueConverter = new ScalarToNodeConverter();
$valueConverter->convert(1); // instanceof IntNode::class
```

## Null value

```php
<?php
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\NullNode;

$valueConverter = new ScalarToNodeConverter();
$valueConverter->convert(null); // instanceof NullNode::class
```

## String value

```php
<?php
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\StringNode;

$valueConverter = new ScalarToNodeConverter();
$valueConverter->convert('string'); // instanceof StringNode::class
```

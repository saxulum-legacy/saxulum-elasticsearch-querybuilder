# IteratableToNodeConverter

## Bool value

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter);
$iteratableConverter->convert(['key' => [bool, 1.234, 1, null, 'string']]); // instanceof ObjectNode::class
```

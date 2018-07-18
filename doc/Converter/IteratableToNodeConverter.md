# IteratableToNodeConverter

**Important**: By default empty nodes get not serialized. NullNode forces null value serialization.

 * ArrayNode (no elements)
 * BoolNode (null)
 * ObjectNode (no elements)
 * FloatNode (null)
 * IntNode (null)
 * StringNode (null)

This works recursive, which means theoretically a multidimensional array can lead into an empty string return.

Check the `allowSerializeEmpty` argument to prevent this if needed.

## Bool value

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter);
$iteratableConverter->convert(['key' => [bool, 1.234, 1, null, 'string']]); // instanceof ObjectNode::class
```

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

## Example

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter());
$test = $iteratableConverter->convert(['key' => [true, 1.234, 1, null, 'string', []]])->serialize(); // instanceof ObjectNode::class
var_dump($test);
```

Returns
```
{
  ["key"]=>
  array(5) {
    [0]=>
    bool(true)
    [1]=>
    float(1.234)
    [2]=>
    int(1)
    [3]=>
    NULL
    [4]=>
    string(6) "string"
  }
}
```

## Example with allowSerializeEmpty

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter());
$test = $iteratableConverter->convert(['key' => [true, 1.234, 1, null, 'string', []]], '', true)->serialize(); // instanceof ObjectNode::class
var_dump($test);
```

Returns
```
{
  ["key"]=>
  array(6) {
    [0]=>
    bool(true)
    [1]=>
    float(1.234)
    [2]=>
    int(1)
    [3]=>
    NULL
    [4]=>
    string(6) "string"
    [5]=>
    array(0) {
    }
  }
}
```
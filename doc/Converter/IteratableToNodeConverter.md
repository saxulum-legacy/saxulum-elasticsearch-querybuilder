# IteratableToNodeConverter

## Without allowSerializeEmpty

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter());
$node = $iteratableConverter->convert([
  'key' => [
      true,
      1.234,
      1,
      null,
      'string',
      []
   ]
]); // instanceof ObjectNode::class
echo $node->json(true));
```

```json
{
  "key": [
      true,
      1.234,
      1,
      null,
      "string"
  ]
}
```

## With allowSerializeEmpty

```php
<?php

use Saxulum\ElasticSearchQueryBuilder\Converter\IteratableToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Converter\ScalarToNodeConverter;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;

$iteratableConverter = new IteratableToNodeConverter(new ScalarToNodeConverter());
$node = $iteratableConverter->convert([
  'key' => [
      true,
      1.234,
      1,
      null,
      'string',
      []
   ]
], true); // instanceof ObjectNode::class
echo $node->json(true));
```

```json
{
  "key": [
      true,
      1.234,
      1,
      null,
      "string",
      []
  ]
}

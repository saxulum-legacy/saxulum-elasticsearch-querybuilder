<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

interface ObjectNodeSerializeInterface
{
    /**
     * @return \stdClass|null
     */
    public function serialize();
}

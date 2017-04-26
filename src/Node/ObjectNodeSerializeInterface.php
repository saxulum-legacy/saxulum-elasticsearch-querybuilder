<?php

declare(strict_types=1);

namespace Saxulum\ElasticSearchQueryBuilder\Node;

interface ObjectNodeSerializeInterface
{
    /**
     * @return \stdClass|null
     */
    public function serialize();

    /**
     * @param boolean $beautify
     * @return string
     */
    public function json(bool $beautify = false): string;
}

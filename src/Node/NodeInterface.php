<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

interface NodeInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return array
     */
    public function serialize();
}

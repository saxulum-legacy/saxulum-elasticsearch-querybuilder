<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class CallbackNode extends AbstractNode
{
    /**
     * @var \Closure
     */
    protected $callback;
    
    public function __construct(\Closure $callback, $allowDefault = false)
    {
        $this->callback = $callback;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return \stdClass|array|string|float|integer|boolean|null
     */
    public function serialize()
    {
        $callback = $this->callback;
        
        return $callback($this);
    }
}

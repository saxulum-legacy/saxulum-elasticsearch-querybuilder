<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

class CallbackNode extends AbstractNode
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param \Closure $callback
     * @param bool     $allowDefault
     */
    public function __construct(\Closure $callback, $allowDefault = false)
    {
        $this->callback = $callback;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @return \stdClass|array|string|float|int|bool|null
     */
    public function serialize()
    {
        $callback = $this->callback;

        return $callback();
    }
}

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
     * @param bool     $allowAddDefault
     */
    public function __construct(\Closure $callback, $allowAddDefault = false)
    {
        $this->callback = $callback;
        $this->allowAddDefault = $allowAddDefault;
    }

    /**
     * @return null
     */
    public function getAddDefault()
    {
        return null;
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

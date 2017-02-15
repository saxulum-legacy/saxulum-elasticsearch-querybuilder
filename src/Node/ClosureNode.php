<?php

namespace Saxulum\ElasticSearchQueryBuilder\Node;

/**
 * @deprecated to complex, better do if/else at usage
 */
class ClosureNode extends AbstractNode
{
    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function getDefault()
    {
        return;
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

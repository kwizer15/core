<?php

namespace Jeedom\Core\Application\Query;

interface QueryDispatcher
{
    /**
     * @param Query $query
     *
     * @return QueryResponse
     */
    public function dispatch($query);
}

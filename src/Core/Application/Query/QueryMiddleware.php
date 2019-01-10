<?php

namespace Jeedom\Core\Application\Query;

interface QueryMiddleware
{
    /**
     * @param Query $query
     * @param QueryHandler $queryHandler
     *
     * @return QueryResponse
     */
    public function process($query, $queryHandler);
}

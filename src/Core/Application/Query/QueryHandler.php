<?php

namespace Jeedom\Core\Application\Query;

interface QueryHandler
{
    /**
     * @param Query $query
     *
     * @return QueryResponse
     */
    public function handle($query);
}

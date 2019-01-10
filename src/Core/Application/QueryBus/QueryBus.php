<?php

namespace Jeedom\Core\Application\QueryBus;

use Jeedom\Core\Application\Query\Query;
use Jeedom\Core\Application\Query\QueryHandler;
use Jeedom\Core\Application\Query\QueryResponse;

class QueryBus implements QueryHandler
{
    private $queryDispatcher;

    /**
     * @var array
     */
    private $queryMiddlewares;

    public function __construct($queryDispatcher, array $queryMiddlewares)
    {
        $this->queryDispatcher = $queryDispatcher;
        $this->queryMiddlewares = $queryMiddlewares;
    }

    /**
     * @param Query $query
     *
     * @return QueryResponse
     */
    public function handle($query)
    {

    }
}

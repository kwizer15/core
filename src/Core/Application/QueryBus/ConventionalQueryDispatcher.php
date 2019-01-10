<?php

namespace Jeedom\Core\Infrastructure\Query;

use Jeedom\Core\Application\Query\Query;
use Jeedom\Core\Application\Query\QueryDispatcher;
use Jeedom\Core\Application\Query\QueryHandler;
use Jeedom\Core\Application\Query\QueryResponse;

class ConventionalQueryDispatcher implements QueryDispatcher
{
    private $handlers = [];

    /**
     * ConventionalQueryDispatcher constructor.
     *
     * @param QueryHandler[] $handlers
     *
     */
    public function __construct(array $handlers)
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof QueryHandler) {
                throw new \LogicException($handler . ' doit implémenter '. QueryHandler::class . '.');
            }
            $handlerClass = \get_class($handler);
            $queryClass = substr($handlerClass, -strlen('Handler'));
            $this->handlers[$queryClass] = $handler;
        }
    }

    /**
     * @param Query $query
     *
     * @return QueryResponse
     */
    public function dispatch($query)
    {
        $queryClass = \get_class($query);
        if (array_key_exists($queryClass, $this->handlers)) {
            return $this->handlers[$queryClass]->handle($query);
        }

        throw new \LogicException('Aucun handler n\'a été trouvé pour gérer ' . $queryClass);
    }
}

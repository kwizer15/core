<?php

namespace Jeedom\Core\Application\QueryBus;

use Jeedom\Core\Application\Query\CommandSubTypesQueryHandler;
use Jeedom\Core\Application\Query\CommandTypesQueryHandler;
use Jeedom\Core\Application\Query\CommandUnitesQueryHandler;
use Jeedom\Core\Infrastructure\Query\ConventionalQueryDispatcher;

class QueryBusFactory
{
    public static function build()
    {
        return new ConventionalQueryDispatcher([
            new CommandTypesQueryHandler(),
            new CommandSubTypesQueryHandler(),
            new CommandUnitesQueryHandler(),
        ]);
    }
}

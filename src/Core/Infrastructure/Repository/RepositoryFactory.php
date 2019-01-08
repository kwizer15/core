<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Domain\Repository\EquipmentLogicRepository;
use Jeedom\Core\Domain\Repository\ScenarioElementRepository;
use Jeedom\Core\Domain\Repository\ScenarioExpressionRepository;

class RepositoryFactory
{
    public static function build($repositoryClass)
    {
        $class = 'test' === getenv('ENV') ? self::testMap($repositoryClass) : self::prodMap($repositoryClass);

        return new $class();
    }

    private static function testMap($repositoryClass)
    {
        $map = [
            CommandRepository::class            => function() { return new InMemoryCommandRepository();            },
            EquipmentLogicRepository::class     => function() { return new InMemoryEquipmentLogicRepository();     },
            ScenarioElementRepository::class    => function() { return new InMemoryScenarioElementRepository();    },
            ScenarioExpressionRepository::class => function() { return new InMemoryScenarioExpressionRepository(); },
        ];

        return $map[$repositoryClass];
    }

    private static function prodMap($repositoryClass)
    {
        $map = [
            CommandRepository::class            => function() { return new DBCommandRepository();            },
            EquipmentLogicRepository::class     => function() { return new DBEquipmentLogicRepository();     },
            ScenarioElementRepository::class    => function() { return new DBScenarioElementRepository();    },
            ScenarioExpressionRepository::class => function() { return new DBScenarioExpressionRepository(); },
        ];

        return $map[$repositoryClass];
    }
}

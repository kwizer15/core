<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Domain\Repository\EquipmentLogicRepository;
use Jeedom\Core\Domain\Repository\ScenarioElementRepository;
use Jeedom\Core\Domain\Repository\ScenarioExpressionRepository;
use Jeedom\Core\Domain\Repository\ScenarioRepository;

class RepositoryFactory
{
    public static function build($repositoryClass)
    {
        $class = 'test' === getenv('ENV') ? self::testMap($repositoryClass) : self::prodMap($repositoryClass);

        return $class();
    }

    private static function testMap($repositoryClass)
    {
        $map = [
            CommandRepository::class            => function() { return new InMemoryCommandRepository();            },
            EquipmentLogicRepository::class     => function() { return new InMemoryEquipmentLogicRepository();     },
            ScenarioRepository::class           => function() { return new InMemoryScenarioRepository();           },
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
            ScenarioRepository::class           => function() { return new DBScenarioRepository();    },
            ScenarioElementRepository::class    => function() { return new DBScenarioElementRepository();    },
            ScenarioExpressionRepository::class => function() { return new DBScenarioExpressionRepository(); },
        ];

        return $map[$repositoryClass];
    }
}

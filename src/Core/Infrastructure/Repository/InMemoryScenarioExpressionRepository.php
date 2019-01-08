<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioExpressionRepository;

class InMemoryScenarioExpressionRepository implements ScenarioExpressionRepository
{
    public function get($id)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($id).')'.PHP_EOL
        ;

        return [];
    }

    public function all()
    {
        echo __CLASS__.'::'.__METHOD__.'()'.PHP_EOL;

        return [];
    }

    public function findByScenarioSubElementId($scenarioSubElementId)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($scenarioSubElementId).')'.PHP_EOL
        ;

        return [];
    }

    public function searchExpression($expression, $options = null, $and = true)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($expression).', '
            .var_export($options).', '
            .var_export($and).')'.PHP_EOL
        ;

        return [];
    }

    public function findByElement($elementId)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($elementId).')'.PHP_EOL
        ;

        return [];
    }
}

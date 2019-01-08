<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioElementRepository;

class InMemoryScenarioElementRepository implements ScenarioElementRepository
{
    /**
     * @param int $id
     *
     * @return \scenarioElement
     * @throws \Exception
     */
    public function get($id)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($id).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param \scenarioElement $scenarioElement
     *
     * @return ScenarioElementRepository
     */
    public function add(\scenarioElement $scenarioElement)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($scenarioElement).')'.PHP_EOL
        ;

        return $this;
    }
}

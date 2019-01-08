<?php

namespace Jeedom\Core\Domain\Repository;

use Jeedom\Common\Domain\Repository\Repository;

interface ScenarioElementRepository extends Repository
{
    /**
     * @param int $id
     *
     * @return \scenarioElement
     */
    public function get($id);

    /**
     * @param \scenarioElement $scenarioElement
     *
     * @return void
     */
    public function add(\scenarioElement $scenarioElement);
}

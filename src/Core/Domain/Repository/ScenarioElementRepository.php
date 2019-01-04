<?php

namespace Jeedom\Common\Domain\Repository;

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
    public function save(\scenarioElement $scenarioElement);
}

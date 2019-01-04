<?php

namespace Jeedom\Core\Domain\Repository;

use Jeedom\Common\Domain\Repository\Repository;

interface ScenarioExpressionRepository extends Repository
{
    public function get($id);

    public function all();

    public function findByScenarioSubElementId($scenarioSubElementId);

    public function searchExpression($expression, $options = null, $and = true);

    public function findByElement($elementId);
}

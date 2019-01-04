<?php

use Jeedom\Core\Infrastructure\Repository\DBScenarioExpressionRepository;

require_once __DIR__ . '/../../core/php/core.inc.php';

$scenarioExpressionRepository = new DBScenarioExpressionRepository();
foreach ($scenarioExpressionRepository->all() as $scenarioExpression) {
	if ($scenarioExpression->getExpression() == 'equipment') {
		try {
			$scenarioExpression->setExpression('equipement');
			$scenarioExpression->save();
		} catch (Exception $e) {

		}
	}
}

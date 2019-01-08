<?php

use Jeedom\Core\Domain\Repository\ScenarioExpressionRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

require_once __DIR__ . '/../../core/php/core.inc.php';

/** @var ScenarioExpressionRepository $scenarioExpressionRepository */
$scenarioExpressionRepository = RepositoryFactory::build(ScenarioExpressionRepository::class);
foreach ($scenarioExpressionRepository->all() as $scenarioExpression) {
	if ($scenarioExpression->getExpression() == 'equipment') {
		try {
			$scenarioExpression->setExpression('equipement');
			$scenarioExpression->save();
		} catch (Exception $e) {

		}
	}
}

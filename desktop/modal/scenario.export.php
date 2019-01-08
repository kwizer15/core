<?php

use Jeedom\Core\Domain\Repository\ScenarioRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

/** @var ScenarioRepository $scenarioRepository */
$scenarioRepository = RepositoryFactory::build(ScenarioRepository::class);
$scenario = $scenarioRepository->get(init('scenario_id'));
if (!is_object($scenario)) {
    throw new Exception('{{Scénario introuvable}}');
}

echo '<pre>' . $scenario->export() . '</pre>';
?>

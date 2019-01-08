<?php

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

require_once __DIR__ . '/../../core/php/core.inc.php';

$commandRepository = RepositoryFactory::build(CommandRepository::class);
foreach ($commandRepository->all() as $cmd) {
	if ($cmd->getDisplay('generic_type') == '') {
		continue;
	}
	$cmd->setGeneric_type($cmd->getDisplay('generic_type'));
    $commandRepository->add($cmd);
}

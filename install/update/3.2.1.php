<?php

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Factory\RepositoryFactory;

require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (cmd::all() as $cmd) {
	if ($cmd->getDisplay('generic_type') == '') {
		continue;
	}
	$cmd->setGeneric_type($cmd->getDisplay('generic_type'));
    RepositoryFactory::build(CommandRepository::class)->add($cmd);
}

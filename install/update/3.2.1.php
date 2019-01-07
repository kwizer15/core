<?php

use Jeedom\Core\Infrastructure\Repository\DBCommandRepository;

require_once __DIR__ . '/../../core/php/core.inc.php';

$commandRepository = new DBCommandRepository();
foreach ($commandRepository->all() as $cmd) {
	if ($cmd->getDisplay('generic_type') == '') {
		continue;
	}
	$cmd->setGeneric_type($cmd->getDisplay('generic_type'));
	$cmd->save();
}

<?php

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
/** @var CommandRepository $commandRepository */
$commandRepository = RepositoryFactory::build(CommandRepository::class);
$cmd = $commandRepository->get(init('cmd_id'));
if (!is_object($cmd)) {
	throw new Exception('Commande non trouvée : ' . init('cmd_id'));
}
?>

  <div style="display: none;" id="md_cmdConfigureSelectMultipleAlert"></div>
  <div>
  	<a class="btn btn-default" id="bt_cmdConfigureSelectMultipleAlertToogle" data-state="0"><i class="far fa-check-circle"></i> {{Basculer}}</a>
  	<a class="btn btn-success pull-right" id="bt_cmdConfigureSelectMultipleAlertApply" style="color : white;" ><i class="fas fa-check"></i> {{Valider}}</a>
  </div>
  <br/>
  <table class="table table-bordered table-condensed tablesorter" id="table_cmdConfigureSelectMultiple">
  	<thead>
  		<tr>
  			<th></th>
  			<th>{{Nom}}</th>
  		</tr>
  	</thead>
  	<tbody>
  		<?php
foreach ($commandRepository->findByTypeSubType($cmd->getType(), $cmd->getSubType()) as $listCmd) {
	echo '<tr data-cmd_id="' . $listCmd->getId() . '">';
	echo '<td>';
	if ($listCmd->getId() == $cmd->getId()) {
		echo '<input type="checkbox" class="selectMultipleApplyCmd" checked/>';
	} else {
		echo '<input type="checkbox" class="selectMultipleApplyCmd" />';
	}
	echo '</td>';
	echo '<td>';
	echo $listCmd->getHumanName(true);
	echo '</td>';
	echo '</tr>';
}
?>
  	</tbody>
  </table>

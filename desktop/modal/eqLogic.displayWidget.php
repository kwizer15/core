<?php

use Jeedom\Core\Domain\Repository\EquipmentLogicRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
/** @var EquipmentLogicRepository $equipmentLogicRepository */
$equipmentLogicRepository = RepositoryFactory::build(EquipmentLogicRepository::class);
$eqLogic = $equipmentLogicRepository->get(init('eqLogic_id'));
if (!is_object($eqLogic)) {
	throw new Exception('EqLogic non trouvé : ' . init('eqLogic_id'));
}
$mc = cache::byKey('widgetHtml' . $eqLogic->getId() . init('version', 'dashboard') . $_SESSION['user']->getId());
if ($mc->getValue() != '') {
	$mc->remove();
}
echo '<center>';
echo '<div>';
echo $eqLogic->toHtml(init('version', 'dashboard'));
echo '</div>';
echo '</center>';
?>
<script>
positionEqLogic();
</script>

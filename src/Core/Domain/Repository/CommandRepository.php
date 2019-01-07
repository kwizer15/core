<?php

namespace Jeedom\Core\Domain\Repository;

use Jeedom\Common\Domain\Repository\Repository;

interface CommandRepository extends Repository
{
    /**
     * @param int $id
     *
     * @return \cmd
     */
    public function get($id);

    public function findByIds($ids);

    public function all();

    public function allHistoryCmd();

    public function findByEqLogicId($eqLogicId, $type = null, $visible = null, $eqLogic = null, $hasGenericType = null);

    public function findByLogicalId($logicalId, $type = null);

    public function findByGenericType($genericType, $eqLogicId = null, $one = false);

    public function searchConfiguration($configuration, $eqType = null);

    public function searchConfigurationEqLogic($eqLogicId, $configuration, $type = null);

    public function searchTemplate($template, $eqType = null, $type = null, $subtype = null);

    public function findByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $multiple = false, $type = null);

    public function findByEqLogicIdAndGenericType($eqLogicId, $genericType, $multiple = false, $type = null);

    public function findByValue($value, $type = null, $onlyEnable = false);

    public function findByTypeEqLogicNameCmdName($eqTypeName, $eqLogicName, $cmdName);

    public function findByEqLogicIdCmdName($eqLogicId, $cmdName);
}

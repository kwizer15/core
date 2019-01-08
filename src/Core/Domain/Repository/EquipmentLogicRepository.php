<?php

namespace Jeedom\Core\Domain\Repository;

use Jeedom\Common\Domain\Repository\Repository;

interface EquipmentLogicRepository extends Repository
{
    public function get($id);

    /**
     * @param bool $onlyEnable
     *
     * @return \eqLogic[]
     */
    public function all($onlyEnable = false);

    public function findByEqRealId($eqReal_id);

    public function findByObjectId($object_id, $onlyEnable = true, $onlyVisible = false, $eqType_name = null, $logicalId = null, $orderByName = false);

    public function findByLogicalId($logicalId, $eqType_name, $multiple = false);

    public function findByType($eqType_name, $onlyEnable = false);

    public function findByCategory($category);

    public function findByTypeAndSearchConfiguration($eqType_name, $configuration);

    public function searchConfiguration($configuration, $type = null);

    public function findByTimeout($timeout = 0, $onlyEnable = false);

    public function findByObjectNameEqLogicName($object_name, $eqLogic_name);
}

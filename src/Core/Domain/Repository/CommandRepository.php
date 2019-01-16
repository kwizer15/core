<?php

namespace Jeedom\Core\Domain\Repository;

interface CommandRepository
{
    /**
     * @param \cmd $cmd
     *
     * @return CommandRepository
     */
    public function add(\cmd $cmd): CommandRepository;

    /**
     * @param \cmd $cmd
     *
     * @return CommandRepository
     */
    public function refresh(\cmd $cmd): CommandRepository;

    /**
     * @param $cmdId
     *
     * @return CommandRepository
     */
    public function remove($cmdId): CommandRepository;

    /**
     * @param $id
     *
     * @return \cmd
     */
    public function get($id);

    /**
     * @param $eqTypeName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByTypeEqLogicNameCmdName($eqTypeName, $eqLogicName, $cmdName);

    /**
     * @param $eqLogicId
     * @param $cmd_name
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdCmdName($eqLogicId, $cmd_name);

    /**
     * @param $objectName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByObjectNameEqLogicNameCmdName($objectName, $eqLogicName, $cmdName);

    /**
     * @param $objectName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByObjectNameCmdName($objectName, $cmdName);

    /**
     * @param $genericType
     * @param null $eqLogicId
     *
     * @return \cmd
     */
    public function findOneByGenericType($genericType, $eqLogicId = null);

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null);

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null);

    /**
     * @param array $ids
     *
     * @return \cmd[]
     */
    public function findByIds($ids): array;

    /**
     * @return \cmd[]
     */
    public function all(): array;

    /**
     * @return \cmd[]
     */
    public function allHistoryCmd(): array;

    /**
     * @param $eqLogicId
     * @param null $type
     * @param null $visible
     * @param null $eqLogic
     * @param null $hasGenericType
     *
     * @return \cmd[]
     */
    public function findByEqLogicId($eqLogicId, $type = null, $visible = null, $eqLogic = null, $hasGenericType = null): array;

    /**
     * @param $logical_id
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByLogicalId($logical_id, $type = null): array;

    /**
     * @param $generic_type
     * @param null $eqLogicId
     * @param bool $one
     *
     * @return \cmd[]
     */
    public function findByGenericType($generic_type, $eqLogicId = null): array;

    /**
     * @param $configuration
     * @param null $eqType
     *
     * @return \cmd[]
     */
    public function searchConfiguration($configuration, $eqType = null): array;

    /**
     * @param $eqLogicId
     * @param $configuration
     * @param null $type
     *
     * @return \cmd[]
     */
    public function searchConfigurationEqLogic($eqLogicId, $configuration, $type = null): array;

    /**
     * @param $template
     * @param null $eqType
     * @param null $type
     * @param null $subtype
     *
     * @return \cmd[]
     */
    public function searchTemplate($template, $eqType = null, $type = null, $subtype = null): array;

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null): array;

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null): array;

    /**
     * @param $value
     * @param null $type
     * @param bool $onlyEnable
     *
     * @return \cmd[]
     */
    public function findByValue($value, $type = null, $onlyEnable = false): array;

    /**
     * @param $type
     * @param string $subType
     *
     * @return \cmd[]
     */
    public function findByTypeSubType($type, $subType = ''): array;

    /**
     * @return string[]
     */
    public function listTypes(): array;

    /**
     * @param $type
     *
     * @return string[]
     */
    public function listSubTypes($type): array;

    /**
     * @return string[]
     */
    public function listUnites(): array;
}

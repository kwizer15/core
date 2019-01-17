<?php

namespace Jeedom\Core\Domain\Repository;

use Jeedom\Core\Domain\Entity\Command;

interface CommandRepository
{
    /**
     * @param Command $cmd
     *
     * @return CommandRepository
     */
    public function add(Command $cmd): CommandRepository;

    /**
     * @param Command $cmd
     *
     * @return CommandRepository
     */
    public function refresh(Command $cmd): CommandRepository;

    /**
     * @param $cmdId
     *
     * @return CommandRepository
     */
    public function remove($cmdId): CommandRepository;

    /**
     * @param $id
     *
     * @return Command
     */
    public function get($id);

    /**
     * @param $eqTypeName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return Command
     */
    public function findOneByTypeEqLogicNameCmdName($eqTypeName, $eqLogicName, $cmdName);

    /**
     * @param $eqLogicId
     * @param $cmd_name
     *
     * @return Command
     */
    public function findOneByEqLogicIdCmdName($eqLogicId, $cmd_name);

    /**
     * @param $objectName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return Command
     */
    public function findOneByObjectNameEqLogicNameCmdName($objectName, $eqLogicName, $cmdName);

    /**
     * @param $objectName
     * @param $cmdName
     *
     * @return Command
     */
    public function findOneByObjectNameCmdName($objectName, $cmdName);

    /**
     * @param $genericType
     * @param null $eqLogicId
     *
     * @return Command
     */
    public function findOneByGenericType($genericType, $eqLogicId = null);

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return Command
     */
    public function findOneByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null);

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return Command
     */
    public function findOneByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null);

    /**
     * @param array $ids
     *
     * @return Command[]
     */
    public function findByIds($ids): array;

    /**
     * @return Command[]
     */
    public function all(): array;

    /**
     * @return Command[]
     */
    public function allHistoryCmd(): array;

    /**
     * @param $eqLogicId
     * @param null $type
     * @param null $visible
     * @param null $eqLogic
     * @param null $hasGenericType
     *
     * @return Command[]
     */
    public function findByEqLogicId($eqLogicId, $type = null, $visible = null, $eqLogic = null, $hasGenericType = null): array;

    /**
     * @param $logical_id
     * @param null $type
     *
     * @return Command[]
     */
    public function findByLogicalId($logical_id, $type = null): array;

    /**
     * @param $generic_type
     * @param null $eqLogicId
     * @param bool $one
     *
     * @return Command[]
     */
    public function findByGenericType($generic_type, $eqLogicId = null): array;

    /**
     * @param $configuration
     * @param null $eqType
     *
     * @return Command[]
     */
    public function searchConfiguration($configuration, $eqType = null): array;

    /**
     * @param $eqLogicId
     * @param $configuration
     * @param null $type
     *
     * @return Command[]
     */
    public function searchConfigurationEqLogic($eqLogicId, $configuration, $type = null): array;

    /**
     * @param $template
     * @param null $eqType
     * @param null $type
     * @param null $subtype
     *
     * @return Command[]
     */
    public function searchTemplate($template, $eqType = null, $type = null, $subtype = null): array;

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return Command[]
     */
    public function findByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null): array;

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return Command[]
     */
    public function findByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null): array;

    /**
     * @param $value
     * @param null $type
     * @param bool $onlyEnable
     *
     * @return Command[]
     */
    public function findByValue($value, $type = null, $onlyEnable = false): array;

    /**
     * @param $type
     * @param string $subType
     *
     * @return Command[]
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

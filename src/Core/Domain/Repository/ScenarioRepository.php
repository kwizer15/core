<?php

namespace Jeedom\Core\Domain\Repository;

interface ScenarioRepository
{
    /**
     * Renvoie un objet scenario
     *
     * @param int $id id du scenario voulu
     *
     * @return \scenario|null object scenario
     */
    public function get($id);

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $group
     * @param null $type
     *
     * @return \scenario[]
     */
    public function all($group = '', $type = null): array;

    /**
     *
     * @return \scenario[]
     */
    public function schedule(): array;

    /**
     *
     * @param string $cmdId
     * @param bool $onlyEnable
     *
     * @return \scenario[]
     */
    public function findByTrigger($cmdId, $onlyEnable = true): array;

    /**
     *
     * @param string $elementId
     *
     * @return \scenario|null
     */
    public function findOneByElement($elementId);

    /**
     *
     * @param string|int $objectId
     * @param bool $onlyEnable
     * @param bool $onlyVisible
     *
     * @return \scenario[]
     */
    public function findByObjectId($objectId, $onlyEnable = true, $onlyVisible = false): array;

    /**
     * @param string $objectName
     * @param string $groupName
     * @param string $scenarioName
     *
     * @return \scenario|null
     */
    public function findOneByObjectNameGroupNameScenarioName($objectName, $groupName, $scenarioName);

    /**
     * @param \scenario $scenario
     *
     * @return void
     */
    public function add(\scenario $scenario);

    /**
     * @param \scenario $scenario
     *
     * @return void
     */
    public function refresh(\scenario $scenario);

    /**
     *
     * @param $scenarioId
     *
     * @return void
     */
    public function remove($scenarioId);

    /**
     *
     * @param null|string $group
     *
     * @return string[]
     */
    public function listGroup($group = null): array;

    /**
     * @return void
     */
    public function cleanTable();
}

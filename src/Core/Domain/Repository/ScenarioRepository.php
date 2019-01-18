<?php

namespace Jeedom\Core\Domain\Repository;

interface ScenarioRepository
{
    /**
     * Renvoie un objet scenario
     *
     * @param int $id id du scenario voulu
     *
     * @return \scenario object scenario
     * @throws \Exception
     */
    public function get($id);

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $group
     * @param null $type
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function all($group = '', $type = null);

    /**
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function schedule();

    /**
     *
     * @param string $cmdId
     * @param bool $onlyEnable
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function findByTrigger($cmdId, $onlyEnable = true);

    /**
     *
     * @param string $elementId
     *
     * @return \scenario
     * @throws \Exception
     */
    public function findOneByElement($elementId);

    /**
     *
     * @param string|int $objectId
     * @param bool $onlyEnable
     * @param bool $onlyVisible
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function findByObjectId($objectId, $onlyEnable = true, $onlyVisible = false);

    /**
     * @param string $objectName
     * @param string $groupName
     * @param string $scenarioName
     *
     * @return \scenario
     * @throws \Exception
     */
    public function findOneByObjectNameGroupNameScenarioName($objectName, $groupName, $scenarioName);

    /**
     * @param \scenario $scenario
     */
    public function add(\scenario $scenario);

    /**
     * @param \scenario $scenario
     *
     * @throws \Exception
     */
    public function refresh(\scenario $scenario);

    /**
     *
     * @param $scenarioId
     *
     * @return bool
     * @throws \Exception
     */
    public function remove($scenarioId);

    /**
     *
     * @param null|string $group
     *
     * @return string[]
     * @throws \Exception
     */
    public function listGroup($group = null);

    /**
     * @return void
     */
    public function cleanTable();
}

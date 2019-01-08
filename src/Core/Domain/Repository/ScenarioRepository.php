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
     * @throws \ReflectionException
     */
    public function get($id);

    public function findByString($string);

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $group
     * @param null $type
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function all($group = '', $type = null);

    /**
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function schedule();

    /**
     *
     * @param type $cmdId
     * @param bool $onlyEnable
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function findByTrigger($cmdId, $onlyEnable = true);

    /**
     *
     * @param type $elementId
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function findByElementId($elementId);

    /**
     *
     * @param $objectId
     * @param bool $onlyEnable
     * @param bool $onlyVisible
     *
     * @return \scenario[]
     */
    public function findByObjectId($objectId, $onlyEnable = true, $onlyVisible = false);

    /**
     * @param $objectName
     * @param $groupName
     * @param $scenarioName
     *
     * @return \scenario
     */
    public function findByObjectNameGroupNameScenarioName($objectName, $groupName, $scenarioName);

    /**
     *
     * @param array $search
     * @return \scenario[]
     */
    public static function searchByUse($search);

    /**
     * @return int
     */
    public function count();
}

<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioRepository;

class SQLDatabaseScenarioRepository implements ScenarioRepository
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * Renvoie un objet scenario
     *
     * @param int $id id du scenario voulu
     *
     * @return \scenario object scenario
     * @throws \Exception
     */
    public function get($id)
    {
        $values = array(
            'id' => $id,
        );
        $sql = 'SELECT ' . $this->getFields() . '
		FROM scenario
		WHERE id=:id';

        return $this->getOneResult($sql, $values);
    }

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $group
     * @param null $type
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function all($group = '', $type = null)
    {
        $values = array();
        if ($group === '') {
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' WHERE `type`=:type';
            }
            $sql .= ' ORDER BY ob.name,s.group, s.name';
            $result1 = $this->getResults($sql, $values);
            if (!is_array($result1)) {
                $result1 = array();
            }
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			WHERE s.object_id IS NULL';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY s.group, s.name';
            $result2 = $this->getResults($sql, $values);
            return array_merge($result1, $result2);
        } elseif ($group === null) {
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE (`group` IS NULL OR `group` = "")';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY s.group, s.name';
            $result1 = $this->getResults($sql, $values);
            if (!is_array($result1)) {
                $result1 = array();
            }
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			WHERE (`group` IS NULL OR `group` = "")
			AND s.object_id IS NULL';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY  s.name';
            $result2 = $this->getResults($sql, $values);
            return array_merge($result1, $result2);
        } else {
            $values = array(
                'group' => $group,
            );
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE `group`=:group';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY ob.name,s.group, s.name';
            $result1 = $this->getResults($sql, $values);
            $sql = 'SELECT ' . $this->getFields('s') . '
			FROM scenario s
			WHERE `group`=:group
			AND s.object_id IS NULL';
            if ($type !== null) {
                $values['type'] = $type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY s.group, s.name';
            $result2 = $this->getResults($sql, $values);
            return array_merge($result1, $result2);
        }
    }

    /**
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function schedule()
    {
        $sql = 'SELECT ' . $this->getFields() . '
		FROM scenario
		WHERE `mode` != "provoke"
		AND isActive=1';
        return \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     *
     * @param string $cmdId
     * @param bool $onlyEnable
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function findByTrigger($cmdId, $onlyEnable = true)
    {
        $values = array(
            'cmd_id' => '%#' . $cmdId . '#%',
        );
        $sql = 'SELECT ' . $this->getFields() . '
		FROM scenario
		WHERE mode != "schedule"';
        if ($onlyEnable) {
            $sql .= ' AND isActive=1';
        }
        $sql .= ' AND `trigger` LIKE :cmd_id';
        return $this->getResults($sql, $values);
    }

    /**
     *
     * @param string $elementId
     *
     * @return \scenario
     * @throws \Exception
     */
    public function findOneByElement($elementId)
    {
        $values = array(
            'element_id' => '%"' . $elementId . '"%',
        );
        $sql = 'SELECT ' . $this->getFields() . '
		FROM scenario
		WHERE `scenarioElement` LIKE :element_id';
        return $this->getOneResult($sql, $values);
    }

    /**
     *
     * @param string|int $objectId
     * @param bool $onlyEnable
     * @param bool $onlyVisible
     *
     * @return \scenario[]
     * @throws \Exception
     */
    public function findByObjectId($objectId, $onlyEnable = true, $onlyVisible = false)
    {
        $values = array();
        $sql = 'SELECT ' . $this->getFields() . '
		FROM scenario';
        if ($objectId === null) {
            $sql .= ' WHERE object_id IS NULL';
        } else {
            $values['object_id'] = $objectId;
            $sql .= ' WHERE object_id=:object_id';
        }
        if ($onlyEnable) {
            $sql .= ' AND isActive = 1';
        }
        if ($onlyVisible) {
            $sql .= ' AND isVisible = 1';
        }
        return $this->getResults($sql, $values);
    }

    /**
     * @param string $objectName
     * @param string $groupName
     * @param string $scenarioName
     *
     * @return \scenario
     * @throws \Exception
     */
    public function findOneByObjectNameGroupNameScenarioName($objectName, $groupName, $scenarioName)
    {
        $values = array(
            'scenario_name' => html_entity_decode($scenarioName),
        );

        if ($objectName == __('Aucun', __FILE__)) {
            if ($groupName == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . $this->getFields('s') . '
				FROM scenario s
				WHERE s.name=:scenario_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
				AND s.object_id IS NULL';
            } else {
                $values['group_name'] = $groupName;
                $sql = 'SELECT ' . $this->getFields('s') . '
				FROM scenario s
				WHERE s.name=:scenario_name
				AND s.object_id IS NULL
				AND `group`=:group_name';
            }
        } else {
            $values['object_name'] = $objectName;
            if ($groupName == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . $this->getFields('s') . '
				FROM scenario s
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
            } else {
                $values['group_name'] = $groupName;
                $sql = 'SELECT ' . $this->getFields('s') . '
				FROM scenario s
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND `group`=:group_name';
            }
        }
        return $this->getOneResult($sql, $values);
    }

    /**
     * @param \scenario $scenario
     */
    public function add(\scenario $scenario)
    {
        if ($scenario->getLastLaunch() == '' && ($scenario->getMode() == 'schedule' || $scenario->getMode() == 'all')) {
            $calculateScheduleDate = $scenario->calculateScheduleDate();
            $scenario->setLastLaunch($calculateScheduleDate['prevDate']);
        }
        \DB::save($scenario);

        // TODO: Ce qui suit doit aller dans un CommandHandler
        $scenario->emptyCacheWidget();
        if ($scenario->hasChanged()) {
            \event::add('scenario::update', array(
                'scenario_id' => $scenario->getId(),
                'isActive' => $scenario->getIsActive(),
                'state' => $scenario->getState(),
                'lastLaunch' => $scenario->getLastLaunch()
            ));
        }
    }

    /**
     * @param \scenario $scenario
     *
     * @throws \Exception
     */
    public function refresh(\scenario $scenario)
    {
        \DB::refresh($scenario);
    }

    /**
     *
     * @param $scenarioId
     *
     * @return bool
     * @throws \Exception
     */
    public function remove($scenarioId)
    {
        \viewData::removeByTypeLinkId('scenario', $scenarioId);
        \dataStore::removeByTypeLinkId('scenario', $scenarioId);
        $scenario = $this->get($scenarioId);
        foreach ($scenario->getElement() as $element) {
            $element->remove();
        }
        $scenario->emptyCacheWidget();
        if (file_exists(dirname(__DIR__, 4) . '/log/scenarioLog/scenario' . $scenarioId . '.log')) {
            unlink(dirname(__DIR__, 4) . '/log/scenarioLog/scenario' . $scenarioId . '.log');
        }
        \cache::delete('scenarioCacheAttr' . $scenarioId);
        \jeedom::addRemoveHistory([
            'id' => $scenarioId,
            'name' => $scenario->getHumanName(),
            'date' => date('Y-m-d H:i:s'),
            'type' => 'scenario'
        ]);
        return \DB::remove($this);
    }

    /**
     *
     * @param null|string $group
     *
     * @return string[]
     * @throws \Exception
     */
    public function listGroup($group = null)
    {
        $values = array();
        $sql = 'SELECT DISTINCT(`group`)
		FROM scenario';
        if ($group !== null) {
            $values['group'] = '%' . $group . '%';
            $sql .= ' WHERE `group` LIKE :group';
        }
        $sql .= ' ORDER BY `group`';
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);
    }

    /**
     *
     */
    public function cleanTable() {
        $ids = array(
            'element' => array(),
            'subelement' => array(),
            'expression' => array(),
        );
        foreach ($this->all() as $scenario) {
            foreach ($scenario->getElement() as $element) {
                $result = $element->getAllId();
                $ids['element'] = array_merge($ids['element'], $result['element']);
                $ids['subelement'] = array_merge($ids['subelement'], $result['subelement']);
                $ids['expression'] = array_merge($ids['expression'], $result['expression']);
            }
        }

        $sql = 'DELETE FROM scenarioExpression WHERE id NOT IN (-1';
        foreach ($ids['expression'] as $expression_id) {
            $sql .= ',' . $expression_id;
        }
        $sql .= ')';
        \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioSubElement WHERE id NOT IN (-1';
        foreach ($ids['subelement'] as $subelement_id) {
            $sql .= ',' . $subelement_id;
        }
        $sql .= ')';
        \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioElement WHERE id NOT IN (-1';
        foreach ($ids['element'] as $element_id) {
            $sql .= ',' . $element_id;
        }
        $sql .= ')';
        \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);
    }

    private function getFields($alias = '')
    {
        if (null === $this->fields[$alias]) {
            $this->fields[$alias] = \DB::buildField(\scenario::class, $alias);
        }

        return $this->fields[$alias];
    }
    
    private function getResults($sql, $values)
    {
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }
    
    private function getOneResult($sql, $values)
    {
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenario::class);
    }
}

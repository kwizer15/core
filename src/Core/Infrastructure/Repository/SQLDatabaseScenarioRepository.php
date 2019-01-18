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
     * {@inheritdoc}
     * @throws \Exception
     */
    public function get($id)
    {
        $values = [
            'id' => $id,
        ];
        $sql = $this->getBaseSQL() . ' WHERE id=:id';

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function all($group = '', $type = null): array
    {
        $values = [];
        $sql = $this->getBaseSQL('s') . ' INNER JOIN object ob ON s.object_id=ob.id';

        $typeRule= '';
        if ($type !== null) {
            $values['type'] = $type;
            $typeRule = ' AND `type`=:type';
        }

        if ($group === null) {
            $sql .= ' WHERE (`group` IS NULL OR `group` = "")';
        } elseif ($group !== '') {
            $values['group'] = $group;
            $sql .= ' WHERE `group`=:group';
        } else {
            $sql .= ' WHERE 1';
        }

        $sql .= $typeRule . ' ORDER BY ';
        if ($group !== null) {
            $sql .= 'ob.name, ';
        }

        $sql .= 's.group, s.name';
        $result1 = $this->getResults($sql, $values);

        if (!is_array($result1)) {
            $result1 = [];
        }

        $sql = $this->getBaseSQL('s') . ' WHERE ';

        if ($group === '') {
            $sql .= 's.object_id IS NULL';
            $sql .= $typeRule . ' ORDER BY s.group, s.name';

        } elseif ($group === null) {
            $sql .= '(`group` IS NULL OR `group` = "") AND s.object_id IS NULL';
            $sql .= $typeRule . ' ORDER BY  s.name';
        } else {
            $sql .= '`group`=:group AND s.object_id IS NULL';
            $sql .= $typeRule . ' ORDER BY s.group, s.name';
        }
        $result2 = $this->getResults($sql, $values);

        return array_merge($result1, $result2);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function schedule(): array
    {
        $sql = $this->getBaseSQL() . '
		WHERE `mode` != "provoke"
		AND isActive=1';
        return $this->getResults($sql);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByTrigger($cmdId, $onlyEnable = true): array
    {
        $values = array(
            'cmd_id' => '%#' . $cmdId . '#%',
        );
        $sql = $this->getBaseSQL() . '
		WHERE mode != "schedule"';
        if ($onlyEnable) {
            $sql .= ' AND isActive=1';
        }
        $sql .= ' AND `trigger` LIKE :cmd_id';
        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByElement($elementId)
    {
        $values = array(
            'element_id' => '%"' . $elementId . '"%',
        );
        $sql = $this->getBaseSQL() . '
		WHERE `scenarioElement` LIKE :element_id';
        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByObjectId($objectId, $onlyEnable = true, $onlyVisible = false): array
    {
        $values = array();
        $sql = $this->getBaseSQL();
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
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByObjectNameGroupNameScenarioName($objectName, $groupName, $scenarioName)
    {
        $values = array(
            'scenario_name' => html_entity_decode($scenarioName),
        );

        if ($objectName == __('Aucun', __FILE__)) {
            if ($groupName == __('Aucun', __FILE__)) {
                $sql = $this->getBaseSQL('s') . '
				WHERE s.name=:scenario_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
				AND s.object_id IS NULL';
            } else {
                $values['group_name'] = $groupName;
                $sql = $this->getBaseSQL('s') . '
				WHERE s.name=:scenario_name
				AND s.object_id IS NULL
				AND `group`=:group_name';
            }
        } else {
            $values['object_name'] = $objectName;
            if ($groupName == __('Aucun', __FILE__)) {
                $sql = $this->getBaseSQL('s') . '
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
            } else {
                $values['group_name'] = $groupName;
                $sql = $this->getBaseSQL('s') . '
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND `group`=:group_name';
            }
        }
        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     * @throws \Exception
     */
    public function refresh(\scenario $scenario)
    {
        \DB::refresh($scenario);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function remove($scenarioId)
    {
        \viewData::removeByTypeLinkId('scenario', $scenarioId);
        \dataStore::removeByTypeLinkId('scenario', $scenarioId);
        $scenario = $this->get($scenarioId);
        if (null === $scenario) {
            return;
        }
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
        \DB::remove($this);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function listGroup($group = null): array
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
     * {@inheritdoc}
     * @throws \Exception
     */
    public function cleanTable() {
        $elements = [];
        $subElements = [];
        $expression = [];
        foreach ($this->all() as $scenario) {
            foreach ($scenario->getElement() as $element) {
                $result = $element->getAllId();
                $elements[] = $result['element'];
                $subElements[] = $result['subelement'];
                $expression[] = $result['expression'];
            }
        }

        $sql = 'DELETE FROM scenarioExpression WHERE id NOT IN ('
            . implode(', ', array_merge([], ...$expression))
            . ')';
        \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioSubElement WHERE id NOT IN ('
            . implode(', ', array_merge([], ...$subElements))
            . ')';
        \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);

        $sql = 'DELETE FROM scenarioElement WHERE id NOT IN ('
            . implode(', ', array_merge([], ...$elements))
            . ')';
        \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);
    }

    /**
     * @param string $alias
     *
     * @return mixed
     */
    private function getFields($alias = '')
    {
        if (null === $this->fields[$alias]) {
            $this->fields[$alias] = \DB::buildField(\scenario::class, $alias);
        }

        return $this->fields[$alias];
    }

    private function getBaseSQL($alias = ''): string
    {
        return 'SELECT ' . $this->getFields($alias) . ' FROM scenario ' . $alias;
    }

    /**
     * @param string $sql
     * @param array $values
     *
     * @return \scenario[]
     * @throws \Exception
     */
    private function getResults($sql, array $values = []): array
    {
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     * @param string $sql
     * @param array $values
     *
     * @return \scenario|null
     * @throws \Exception
     */
    private function getOneResult($sql, array $values = [])
    {
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenario::class);
    }
}

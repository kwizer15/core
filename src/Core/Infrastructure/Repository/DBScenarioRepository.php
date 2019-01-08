<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

class DBScenarioRepository implements ScenarioRepository
{
    /**
     * Renvoie un objet scenario
     *
     * @param int $_id id du scenario voulu
     *
     * @return \scenario object scenario
     * @throws \ReflectionException
     */
    public function get($_id)
    {
        $values = [
            'id' => $_id,
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenario::class) . '
		FROM scenario
		WHERE id=:id';

        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenario::class);
    }

    public function findByString($_string)
    {
        $scenario = $this->get(str_replace('#scenario', '', $this->fromHumanReadable($_string)));
        if (!is_object($scenario)) {
            throw new \Exception(__('La commande n\'a pas pu être trouvée : ', __FILE__) . $_string . __(' => ', __FILE__) . $this->fromHumanReadable($_string));
        }

        return $scenario;
    }

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $_group
     * @param null $_type
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function all($_group = '', $_type = null)
    {
        $values = [];
        if ($_group === '') {
            $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' WHERE `type`=:type';
            }
            $sql .= ' ORDER BY ob.name,s.group, s.name';
            $result1 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
            if (!is_array($result1)) {
                $result1 = [];
            }
            $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
			FROM scenario s
			WHERE s.object_id IS NULL';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY s.group, s.name';
            $result2 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);

            return array_merge($result1, $result2);
        }

        if ($_group === null) {
            $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE (`group` IS NULL OR `group` = "")';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY s.group, s.name';
            $result1 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
            if (!is_array($result1)) {
                $result1 = [];
            }
            $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
			FROM scenario s
			WHERE (`group` IS NULL OR `group` = "")
			AND s.object_id IS NULL';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND `type`=:type';
            }
            $sql .= ' ORDER BY  s.name';
            $result2 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);

            return array_merge($result1, $result2);
        }

        $values = [
            'group' => $_group,
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
        FROM scenario s
        INNER JOIN object ob ON s.object_id=ob.id
        WHERE `group`=:group';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        $sql .= ' ORDER BY ob.name,s.group, s.name';
        $result1 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
        $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
        FROM scenario s
        WHERE `group`=:group
        AND s.object_id IS NULL';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        $sql .= ' ORDER BY s.group, s.name';
        $result2 = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);

        return array_merge($result1, $result2);
    }

    /**
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function schedule()
    {
        $sql = 'SELECT ' . Connection::buildField(\scenario::class) . '
		FROM scenario
		WHERE `mode` != "provoke"
		AND isActive=1';
        return Connection::Prepare($sql, [], Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     *
     * @param type $_cmd_id
     * @param bool $_onlyEnable
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function findByTrigger($_cmd_id, $_onlyEnable = true)
    {
        $values = [
            'cmd_id' => '%#' . $_cmd_id . '#%',
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenario::class) . '
		FROM scenario
		WHERE mode != "schedule"';
        if ($_onlyEnable) {
            $sql .= ' AND isActive=1';
        }
        $sql .= ' AND `trigger` LIKE :cmd_id';
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     *
     * @param type $_element_id
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function findByElementId($_element_id)
    {
        $values = [
            'element_id' => '%"' . $_element_id . '"%',
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenario::class) . '
		FROM scenario
		WHERE `scenarioElement` LIKE :element_id';

        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     *
     * @param type $_object_id
     * @param bool $_onlyEnable
     * @param bool $_onlyVisible
     *
     * @return \scenario[]
     * @throws \ReflectionException
     */
    public function findByObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false)
    {
        $values = [];
        $sql = 'SELECT ' . Connection::buildField(\scenario::class) . '
		FROM scenario';
        if ($_object_id === null) {
            $sql .= ' WHERE object_id IS NULL';
        } else {
            $values['object_id'] = $_object_id;
            $sql .= ' WHERE object_id=:object_id';
        }
        if ($_onlyEnable) {
            $sql .= ' AND isActive = 1';
        }
        if ($_onlyVisible) {
            $sql .= ' AND isVisible = 1';
        }

        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     * @param object $_object_name
     * @param type $_group_name
     * @param type $_scenario_name
     *
     * @return \scenario
     * @throws \ReflectionException
     */
    public function findByObjectNameGroupNameScenarioName($_object_name, $_group_name, $_scenario_name)
    {
        $values = [
            'scenario_name' => html_entity_decode($_scenario_name),
        ];

        if ($_object_name == __('Aucun', __FILE__)) {
            if ($_group_name == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
				FROM scenario s
				WHERE s.name=:scenario_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
				AND s.object_id IS NULL';
            } else {
                $values['group_name'] = $_group_name;
                $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
				FROM scenario s
				WHERE s.name=:scenario_name
				AND s.object_id IS NULL
				AND `group`=:group_name';
            }
        } else {
            $values['object_name'] = $_object_name;
            if ($_group_name == __('Aucun', __FILE__)) {
                $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
				FROM scenario s
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
            } else {
                $values['group_name'] = $_group_name;
                $sql = 'SELECT ' . Connection::buildField(\scenario::class, 's') . '
				FROM scenario s
				INNER JOIN object ob ON s.object_id=ob.id
				WHERE s.name=:scenario_name
				AND ob.name=:object_name
				AND `group`=:group_name';
            }
        }

        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenario::class);
    }

    /**
     *
     * @param mixed $_input
     *
     * @return string
     * @throws \ReflectionException
     */
    private function fromHumanReadable($_input)
    {
        $isJson = false;
        if (is_json($_input)) {
            $isJson = true;
            $_input = json_decode($_input, true);
        }
        if (is_object($_input)) {
            $reflections = [];
            $uuid = spl_object_hash($_input);
            if (!isset($reflections[$uuid])) {
                $reflections[$uuid] = new \ReflectionClass($_input);
            }
            $reflection = $reflections[$uuid];
            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($_input);
                $property->setValue($_input, $this->fromHumanReadable($value));
                $property->setAccessible(false);
            }
            return $_input;
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = $this->fromHumanReadable($value);
            }
            if ($isJson) {
                return json_encode($_input, JSON_UNESCAPED_UNICODE);
            }
            return $_input;
        }
        $text = $_input;

        preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
        if (\count($matches) === 4) {
            $countMatches = count($matches[0]);
            for ($i = 0; $i < $countMatches; $i++) {
                if (isset($matches[1][$i], $matches[2][$i], $matches[3][$i])) {
                    $scenario = $this->findByObjectNameGroupNameScenarioName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
                    if (is_object($scenario)) {
                        $text = str_replace($matches[0][$i], '#scenario' . $scenario->getId() . '#', $text);
                    }
                }
            }
        }

        return $text;
    }

    /**
     *
     * @param array $searchs
     * @return \scenario[]
     */
    public static function searchByUse($searchs)
    {
        $return = [];
        $expressions = [];
        $scenarios = [];
        foreach ($searchs as $search) {
            $_cmd_id = str_replace('#', '', $search['action']);
            $return = array_merge($return, $this->findByTrigger($_cmd_id, false));
            if (!isset($search['and'])) {
                $search['and'] = false;
            }
            if (!isset($search['option'])) {
                $search['option'] = $search['action'];
            }
            $expressions = array_merge($expressions, $this->searchExpression($search['action'], $search['option'], $search['and']));
        }
        if (\is_array($expressions) && \count($expressions) > 0) {
            foreach ($expressions as $expression) {
                $scenarios[] = $expression->getSubElement()->getElement()->getScenario();
            }
        }

        if (!\is_array($scenarios) || \count($scenarios) <= 0) {
            return $return;
        }

        foreach ($scenarios as $scenario) {
            if (is_object($scenario)) {
                $find = false;
                foreach ($return as $existScenario) {
                    if ($scenario->getId() == $existScenario->getId()) {
                        $find = true;
                        break;
                    }
                }
                if (!$find) {
                    $return[] = $scenario;
                }
            }
        }

        return $return;
    }

    /**
     * @return int
     * @throws \ReflectionException
     */
    public function count()
    {
        // TODO: à optimiser
        return \count($this->all());
    }
}

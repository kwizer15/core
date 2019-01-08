<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

class DBCommandRepository implements CommandRepository
{
    /**
     * @param int $id
     *
     * @return \cmd
     * @throws \Exception
     */
    public function get($id)
    {
        // FIXME: Retourner un cmd ou renvoyer une exception
        if ($id == '') {
            return;
        }
        $values = array(
            'id' => $id,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE id=:id';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_ids
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByIds($_ids)
    {
        if (!is_array($_ids) || count($_ids) == 0) {
            return;
        }
        $in = trim(implode(',', $_ids), ',');
        if (!empty($in)) {
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE id IN (' . $in . ')';
            return self::cast(Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
    }

    /**
     * @return \cmd[]
     * @throws \Exception
     */
    public function all()
    {
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		ORDER BY id';
        return self::cast(Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @return \cmd[]
     * @throws \Exception
     */
    public function allHistoryCmd()
    {
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE isHistorized=1
		AND type=\'info\'';
        $sql .= ' ORDER BY ob.position,ob.name,el.name,c.name';
        $result1 = self::cast(Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE el.object_id IS NULL
		AND isHistorized=1
		AND type=\'info\'';
        $sql .= ' ORDER BY el.name,c.name';
        $result2 = self::cast(Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        return array_merge($result1, $result2);
    }

    /**
     * @param $_eqLogic_id
     * @param null $_type
     * @param null $_visible
     * @param null $_eqLogic
     * @param null $_has_generic_type
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null)
    {
        $values = array();
        if (is_array($_eqLogic_id)) {
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE eqLogic_id IN (' . implode(',', $_eqLogic_id) . ')';
        } else {
            $values = array(
                'eqLogic_id' => $_eqLogic_id,
            );
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE eqLogic_id=:eqLogic_id';
        }
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        if ($_visible !== null) {
            $sql .= ' AND `isVisible`=1';
        }
        if ($_has_generic_type) {
            $sql .= ' AND `generic_type` IS NOT NULL';
        }
        $sql .= ' ORDER BY `order`,`name`';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class), $_eqLogic);
    }

    /**
     * @param $_logical_id
     * @param null $_type
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByLogicalId($_logical_id, $_type = null)
    {
        $values = array(
            'logicalId' => $_logical_id,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        $sql .= ' ORDER BY `order`';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_generic_type
     * @param null $_eqLogic_id
     * @param bool $_one
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByGenericType($_generic_type, $_eqLogic_id = null, $_one = false)
    {
        if (is_array($_generic_type)) {
            $in = '';
            foreach ($_generic_type as $value) {
                $in .= "'" . $value . "',";
            }
            $values = array();
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE generic_type IN (' . trim($in, ',') . ')';
        } else {
            $values = array(
                'generic_type' => $_generic_type,
            );
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE generic_type=:generic_type';
        }
        if ($_eqLogic_id !== null) {
            $values['eqLogic_id'] = $_eqLogic_id;
            $sql .= ' AND `eqLogic_id`=:eqLogic_id';
        }
        $sql .= ' ORDER BY `order`';
        if ($_one) {
            return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_configuration
     * @param null $_eqType
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function searchConfiguration($_configuration, $_eqType = null)
    {
        if (!is_array($_configuration)) {
            $values = array(
                'configuration' => '%' . $_configuration . '%',
            );
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE configuration LIKE :configuration';
        } else {
            $values = array(
                'configuration' => '%' . $_configuration[0] . '%',
            );
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE configuration LIKE :configuration';
            $countConfigurations = count($_configuration);
            for ($i = 1; $i < $countConfigurations; $i++) {
                $values['configuration' . $i] = '%' . $_configuration[$i] . '%';
                $sql .= ' OR configuration LIKE :configuration' . $i;
            }
        }
        if ($_eqType !== null) {
            $values['eqType'] = $_eqType;
            $sql .= ' AND eqType=:eqType ';
        }
        $sql .= ' ORDER BY name';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_eqLogic_id
     * @param $_configuration
     * @param null $_type
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null)
    {
        $values = array(
            'configuration' => '%' . $_configuration . '%',
            'eqLogic_id' => $_eqLogic_id,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
        }
        $sql .= ' AND configuration LIKE :configuration';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_template
     * @param null $_eqType
     * @param null $_type
     * @param null $_subtype
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null)
    {
        $values = array(
            'template' => '%' . $_template . '%',
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE template LIKE :template';
        if ($_eqType !== null) {
            $values['eqType'] = $_eqType;
            $sql .= ' AND eqType=:eqType ';
        }
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
        }
        if ($_subtype !== null) {
            $values['subType'] = $_subtype;
            $sql .= ' AND subType=:subType ';
        }
        $sql .= ' ORDER BY name';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_eqLogic_id
     * @param $_logicalId
     * @param bool $_multiple
     * @param null $_type
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false, $_type = null)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'logicalId' => $_logicalId,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_eqLogic_id
     * @param $_generic_type
     * @param bool $_multiple
     * @param null $_type
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_multiple = false, $_type = null)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'generic_type' => $_generic_type,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND generic_type=:generic_type';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_value
     * @param null $_type
     * @param bool $_onlyEnable
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByValue($_value, $_type = null, $_onlyEnable = false)
    {
        $values = array(
            'value' => $_value,
            'search' => '%#' . $_value . '#%',
        );

        if ($_onlyEnable) {
            $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE ( value=:value OR value LIKE :search)
			AND el.isEnable=1
			AND c.id!=:value';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND c.type=:type ';
            }
        } else {
            $sql = 'SELECT ' . Connection::buildField(\cmd::class) . '
			FROM cmd
			WHERE ( value=:value OR value LIKE :search)
			AND id!=:value';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND type=:type ';
            }
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_eqType_name
     * @param $_eqLogic_name
     * @param $_cmd_name
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name)
    {
        $values = array(
            'eqType_name' => $_eqType_name,
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE c.name=:cmd_name
		AND el.name=:eqLogic_name
		AND el.eqType_name=:eqType_name';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_eqLogic_id
     * @param $_cmd_name
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByEqLogicIdCmdName($_eqLogic_id, $_cmd_name)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		WHERE c.name=:cmd_name
		AND c.eqLogic_id=:eqLogic_id';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_object_name
     * @param $_eqLogic_name
     * @param $_cmd_name
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name)
    {
        $values = array(
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => (html_entity_decode($_cmd_name) != '') ? html_entity_decode($_cmd_name) : $_cmd_name,
        );

        if ($_object_name == __('Aucun', __FILE__)) {
            $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND el.object_id IS NULL';
        } else {
            $values['object_name'] = $_object_name;
            $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			INNER JOIN object ob ON el.object_id=ob.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND ob.name=:object_name';
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_object_name
     * @param $_cmd_name
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByObjectNameCmdName($_object_name, $_cmd_name)
    {
        $values = array(
            'object_name' => $_object_name,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE c.name=:cmd_name
		AND ob.name=:object_name';
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param $_type
     * @param string $_subType
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByTypeSubType($_type, $_subType = '')
    {
        $values = array(
            'type' => $_type,
        );
        $sql = 'SELECT ' . Connection::buildField(\cmd::class, 'c') . '
		FROM cmd c
		WHERE c.type=:type';
        if ($_subType != '') {
            $values['subtype'] = $_subType;
            $sql .= ' AND c.subtype=:subtype';
        }
        return self::cast(Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param \cmd $command
     *
     * @return bool
     * @throws \Exception
     */
    public function add(\cmd $command)
    {
        if ($command->getName() == '') {
            throw new Exception(__('Le nom de la commande ne peut pas être vide :', __FILE__) . print_r($command, true));
        }
        if ($command->getType() == '') {
            throw new Exception($command->getHumanName() . ' ' . __('Le type de la commande ne peut pas être vide :', __FILE__) . print_r($command, true));
        }
        if ($command->getSubType() == '') {
            throw new Exception($command->getHumanName() . ' ' . __('Le sous-type de la commande ne peut pas être vide :', __FILE__) . print_r($command, true));
        }
        if ($command->getEqLogic_id() == '') {
            throw new Exception($command->getHumanName() . ' ' . __('Vous ne pouvez pas créer une commande sans la rattacher à un équipement', __FILE__));
        }
        if ($command->getConfiguration('maxValue') != ''
            && $command->getConfiguration('minValue') != ''
            && $command->getConfiguration('minValue') > $command->getConfiguration('maxValue')
        ) {
            throw new Exception($command->getHumanName() . ' ' . __('La valeur minimum de la commande ne peut etre supérieure à la valeur maximum', __FILE__));
        }
        if ($command->getEqType() == '') {
            $command->setEqType($command->getEqLogic()->getEqType_name());
        }
        if ($command->getDisplay('generic_type') !== '' && $command->getGeneric_type() == '') {
            $command->setGeneric_type($command->getDisplay('generic_type'));
            $command->setDisplay('generic_type', '');
        }
        // FIXME: La partie validation se fait normalement à la construction de l'objet, en l'état on laisse tel que.
        Connection::save($command);
        if ($command->needsRefreshWidget()) {
            $command->disableRefreshWidget();
            $command->getEqLogic()->refreshWidget();
        }
        if ($command->needsRefreshAlert() && $command->isTypeInfo()) {
            $value = $command->execCmd();
            $level = $command->checkAlertLevel($value);
            if ($level != $command->getCache('alertLevel')) {
                $command->actionAlertLevel($level, $value);
            }
        }
        return $this;
    }

    private static function cast($inputs, $eqLogic = null)
    {
        if (is_object($inputs) && class_exists($inputs->getEqType() . 'Cmd')) {
            if ($eqLogic !== null) {
                $inputs->_eqLogic = $eqLogic;
            }
            return cast($inputs, $inputs->getEqType() . 'Cmd');
        }
        if (is_array($inputs)) {
            $return = array();
            foreach ($inputs as $input) {
                if ($eqLogic !== null) {
                    $input->_eqLogic = $eqLogic;
                }
                $return[] = self::cast($input);
            }
            return $return;
        }
        return $inputs;
    }

    /**
     * @param $id
     *
     * @return CommandRepository
     * @throws \ReflectionException
     */
    public function remove($id)
    {
        $command = $this->get($id);
        \viewData::removeByTypeLinkId('cmd', $id);
        \dataStore::removeByTypeLinkId('cmd', $id);
        $command->getEqLogic()->emptyCacheWidget();
        $command->emptyHistory();
        \cache::delete('cmdCacheAttr' . $id);
        \cache::delete('cmd' . $id);
        \jeedom::addRemoveHistory(array('id' => $id, 'name' => $command->getHumanName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'cmd'));
        Connection::remove($this);

        return $this;
    }

    /**
     * Destinée à disparaitre
     *
     * @param \cmd $command
     *
     * @return mixed
     * @throws \Exception
     */
    public function refresh(\cmd $command)
    {
        Connection::refresh($command);

        return $this;
    }
}

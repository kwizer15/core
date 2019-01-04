<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Common\Domain\Repository\CommandRepository;

class DBCommandRepository implements CommandRepository
{
    /**
     * @param int $id
     *
     * @return \cmd
     */
    public function get($id)
    {
        $class = \cmd::class;
        if ($id == '') {
            return;
        }
        $values = array(
            'id' => $id,
        );
        $sql = 'SELECT ' . \DB::buildField($class) . '
		FROM cmd
		WHERE id=:id';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, $class));
    }


    public function findByIds($_ids)
    {
        if (!is_array($_ids) || count($_ids) == 0) {
            return;
        }
        $in = trim(implode(',', $_ids), ',');
        if (!empty($in)) {
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE id IN (' . $in . ')';
            return self::cast(\DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
    }

    public function all()
    {
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
		FROM cmd
		ORDER BY id';
        return self::cast(\DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function allHistoryCmd()
    {
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE isHistorized=1
		AND type=\'info\'';
        $sql .= ' ORDER BY ob.position,ob.name,el.name,c.name';
        $result1 = self::cast(\DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE el.object_id IS NULL
		AND isHistorized=1
		AND type=\'info\'';
        $sql .= ' ORDER BY el.name,c.name';
        $result2 = self::cast(\DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        return array_merge($result1, $result2);
    }

    public function findByEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null)
    {
        $values = array();
        if (is_array($_eqLogic_id)) {
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE eqLogic_id IN (' . implode(',', $_eqLogic_id) . ')';
        } else {
            $values = array(
                'eqLogic_id' => $_eqLogic_id,
            );
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
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
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class), $_eqLogic);
    }

    public function findByLogicalId($_logical_id, $_type = null)
    {
        $values = array(
            'logicalId' => $_logical_id,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
		FROM cmd
		WHERE logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        $sql .= ' ORDER BY `order`';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByGenericType($_generic_type, $_eqLogic_id = null, $_one = false)
    {
        if (is_array($_generic_type)) {
            $in = '';
            foreach ($_generic_type as $value) {
                $in .= "'" . $value . "',";
            }
            $values = array();
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE generic_type IN (' . trim($in, ',') . ')';
        } else {
            $values = array(
                'generic_type' => $_generic_type,
            );
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE generic_type=:generic_type';
        }
        if ($_eqLogic_id !== null) {
            $values['eqLogic_id'] = $_eqLogic_id;
            $sql .= ' AND `eqLogic_id`=:eqLogic_id';
        }
        $sql .= ' ORDER BY `order`';
        if ($_one) {
            return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function searchConfiguration($_configuration, $_eqType = null)
    {
        if (!is_array($_configuration)) {
            $values = array(
                'configuration' => '%' . $_configuration . '%',
            );
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE configuration LIKE :configuration';
        } else {
            $values = array(
                'configuration' => '%' . $_configuration[0] . '%',
            );
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
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
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null)
    {
        $values = array(
            'configuration' => '%' . $_configuration . '%',
            'eqLogic_id' => $_eqLogic_id,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
        }
        $sql .= ' AND configuration LIKE :configuration';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null)
    {
        $values = array(
            'template' => '%' . $_template . '%',
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
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
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false, $_type = null)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'logicalId' => $_logicalId,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_multiple = false, $_type = null)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'generic_type' => $_generic_type,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND generic_type=:generic_type';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByValue($_value, $_type = null, $_onlyEnable = false)
    {
        $values = array(
            'value' => $_value,
            'search' => '%#' . $_value . '#%',
        );

        if ($_onlyEnable) {
            $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
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
            $sql = 'SELECT ' . \DB::buildField(\cmd::class) . '
			FROM cmd
			WHERE ( value=:value OR value LIKE :search)
			AND id!=:value';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND type=:type ';
            }
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name)
    {
        $values = array(
            'eqType_name' => $_eqType_name,
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE c.name=:cmd_name
		AND el.name=:eqLogic_name
		AND el.eqType_name=:eqType_name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByEqLogicIdCmdName($_eqLogic_id, $_cmd_name)
    {
        $values = array(
            'eqLogic_id' => $_eqLogic_id,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		WHERE c.name=:cmd_name
		AND c.eqLogic_id=:eqLogic_id';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name)
    {
        $values = array(
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => (html_entity_decode($_cmd_name) != '') ? html_entity_decode($_cmd_name) : $_cmd_name,
        );

        if ($_object_name == __('Aucun', __FILE__)) {
            $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND el.object_id IS NULL';
        } else {
            $values['object_name'] = $_object_name;
            $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			INNER JOIN object ob ON el.object_id=ob.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND ob.name=:object_name';
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByObjectNameCmdName($_object_name, $_cmd_name)
    {
        $values = array(
            'object_name' => $_object_name,
            'cmd_name' => $_cmd_name,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE c.name=:cmd_name
		AND ob.name=:object_name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cmd::class));
    }

    public function findByTypeSubType($_type, $_subType = '')
    {
        $values = array(
            'type' => $_type,
        );
        $sql = 'SELECT ' . \DB::buildField(\cmd::class, 'c') . '
		FROM cmd c
		WHERE c.type=:type';
        if ($_subType != '') {
            $values['subtype'] = $_subType;
            $sql .= ' AND c.subtype=:subtype';
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cmd::class));
    }

    /**
     * @param \cmd $command
     *
     * @return void
     */
    public function save(\cmd $command)
    {
        // TODO: Implement save() method.
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
}

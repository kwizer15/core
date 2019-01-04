<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\EquipmentLogicRepository;

class DBEquipmentLogicRepository implements EquipmentLogicRepository
{
    /**
     * @param $id
     *
     * @return \eqLogic
     * @throws \Exception
     */
    public function get($id) {
        // FIXME: Retourner un eqLogic ou renvoyer une exception
        if ($id == '') {
            return;
        }
        $values = array(
            'id' => $id,
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE id=:id';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function all($_onlyEnable = false) {
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class, 'el') . '
        FROM eqLogic el
        LEFT JOIN object ob ON el.object_id=ob.id';
        if ($_onlyEnable) {
            $sql .= ' AND isEnable=1';
        }
        $sql .= ' ORDER BY ob.name,el.name';
        return self::cast(\DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_eqReal_id
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByEqRealId($_eqReal_id) {
        $values = array(
            'eqReal_id' => $_eqReal_id,
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE eqReal_id=:eqReal_id';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_object_id
     * @param bool $_onlyEnable
     * @param bool $_onlyVisible
     * @param null $_eqType_name
     * @param null $_logicalId
     * @param bool $_orderByName
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null, $_orderByName = false) {
        $values = array();
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic';
        if ($_object_id === null) {
            $sql .= ' WHERE object_id IS NULL';
        } else {
            $values['object_id'] = $_object_id;
            $sql .= ' WHERE object_id=:object_id';
        }
        if ($_onlyEnable) {
            $sql .= ' AND isEnable = 1';
        }
        if ($_onlyVisible) {
            $sql .= ' AND isVisible = 1';
        }
        if ($_eqType_name !== null) {
            $values['eqType_name'] = $_eqType_name;
            $sql .= ' AND eqType_name=:eqType_name';
        }
        if ($_logicalId !== null) {
            $values['logicalId'] = $_logicalId;
            $sql .= ' AND logicalId=:logicalId';
        }
        if ($_orderByName) {
            $sql .= ' ORDER BY `name`';
        } else {
            $sql .= ' ORDER BY `order`,category';
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_logicalId
     * @param $_eqType_name
     * @param bool $_multiple
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByLogicalId($_logicalId, $_eqType_name, $_multiple = false) {
        $values = array(
            'logicalId' => $_logicalId,
            'eqType_name' => $_eqType_name,
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE logicalId=:logicalId
        AND eqType_name=:eqType_name';
        if ($_multiple) {
            return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_eqType_name
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByType($_eqType_name, $_onlyEnable = false) {
        $values = array(
            'eqType_name' => $_eqType_name,
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class, 'el') . '
        FROM eqLogic el
        LEFT JOIN object ob ON el.object_id=ob.id
        WHERE eqType_name=:eqType_name ';
        if ($_onlyEnable) {
            $sql .= ' AND isEnable=1';
        }
        $sql .= ' ORDER BY ob.name,el.name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_category
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByCategory($_category) {
        $values = array(
            'category' => '%"' . $_category . '":1%',
            'category2' => '%"' . $_category . '":"1"%',
        );

        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE category LIKE :category
        OR category LIKE :category2
        ORDER BY name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_eqType_name
     * @param $_configuration
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByTypeAndSearchConfiguration($_eqType_name, $_configuration) {
        $values = array(
            'eqType_name' => $_eqType_name,
            'configuration' => '%' . $_configuration . '%',
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE eqType_name=:eqType_name
        AND configuration LIKE :configuration
        ORDER BY name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_configuration
     * @param null $_type
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function searchConfiguration($_configuration, $_type = null) {
        if (!is_array($_configuration)) {
            $values = array(
                'configuration' => '%' . $_configuration . '%',
            );
            $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
			        FROM eqLogic
			        WHERE configuration LIKE :configuration';
        } else {
            $values = array(
                'configuration' => '%' . $_configuration[0] . '%',
            );
            $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
			        FROM eqLogic
			        WHERE configuration LIKE :configuration';
            $configurationCount = \count($_configuration);
            for ($i = 1; $i < $configurationCount; $i++) {
                $values['configuration' . $i] = '%' . $_configuration[$i] . '%';
                $sql .= ' OR configuration LIKE :configuration' . $i;
            }
        }
        if ($_type !== null) {
            $values['eqType_name'] = $_type;
            $sql .= ' AND eqType_name=:eqType_name ';
        }
        $sql .= ' ORDER BY name';
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param int $_timeout
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByTimeout($_timeout = 0, $_onlyEnable = false) {
        $values = array(
            'timeout' => $_timeout,
        );
        $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
        FROM eqLogic
        WHERE timeout>=:timeout';
        if ($_onlyEnable) {
            $sql .= ' AND isEnable=1';
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    /**
     * @param $_object_name
     * @param $_eqLogic_name
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByObjectNameEqLogicName($_object_name, $_eqLogic_name) {
        // FIXME: Aucune idée de ce que c'est !
        if ($_object_name == __('Aucun', __FILE__)) {
            $values = array(
                'eqLogic_name' => $_eqLogic_name,
            );
            $sql = 'SELECT ' . \DB::buildField(\eqLogic::class) . '
            FROM eqLogic
            WHERE name=:eqLogic_name
            AND object_id IS NULL';
        } else {
            $values = array(
                'eqLogic_name' => $_eqLogic_name,
                'object_name' => $_object_name,
            );
            $sql = 'SELECT ' . \DB::buildField(\eqLogic::class, 'el') . '
            FROM eqLogic el
            INNER JOIN object ob ON el.object_id=ob.id
            WHERE el.name=:eqLogic_name
            AND ob.name=:object_name';
        }
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \eqLogic::class));
    }

    private static function cast($inputs) {
        if (is_object($inputs) && class_exists($inputs->getEqType_name())) {
            return cast($inputs, $inputs->getEqType_name());
        }
        if (is_array($inputs)) {
            $return = array();
            foreach ($inputs as $input) {
                $return[] = self::cast($input);
            }
            return $return;
        }
        return $inputs;
    }
}

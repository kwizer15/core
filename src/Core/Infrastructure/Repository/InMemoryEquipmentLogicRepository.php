<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\EquipmentLogicRepository;

class InMemoryEquipmentLogicRepository implements EquipmentLogicRepository
{
    /**
     * @param $id
     *
     * @return \eqLogic
     * @throws \Exception
     */
    public function get($id) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($id).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function all($_onlyEnable = false) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_onlyEnable).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_eqReal_id
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByEqRealId($_eqReal_id) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqReal_id).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_id).', '
            .var_export($_onlyEnable).', '
            .var_export($_onlyVisible).', '
            .var_export($_eqType_name).', '
            .var_export($_logicalId).', '
            .var_export($_orderByName).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_logicalId).', '
            .var_export($_eqType_name).', '
            .var_export($_multiple).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_eqType_name
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByType($_eqType_name, $_onlyEnable = false) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqType_name).', '
            .var_export($_onlyEnable).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_category
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByCategory($_category) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_category).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_eqType_name
     * @param $_configuration
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByTypeAndSearchConfiguration($_eqType_name, $_configuration) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqType_name).', '
            .var_export($_configuration).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_configuration
     * @param null $_type
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function searchConfiguration($_configuration, $_type = null) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_configuration).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param int $_timeout
     * @param bool $_onlyEnable
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByTimeout($_timeout = 0, $_onlyEnable = false) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_timeout).', '
            .var_export($_onlyEnable).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param $_object_name
     * @param $_eqLogic_name
     *
     * @return \eqLogic[]
     * @throws \Exception
     */
    public function findByObjectNameEqLogicName($_object_name, $_eqLogic_name) {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_name).', '
            .var_export($_eqLogic_name).')'.PHP_EOL
        ;

        return [];
    }
}

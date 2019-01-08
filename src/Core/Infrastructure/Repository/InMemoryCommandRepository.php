<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

// TODO: à impléméenter
class InMemoryCommandRepository implements CommandRepository
{
    /**
     * @param int $id
     *
     * @return \cmd
     * @throws \Exception
     */
    public function get($id)
    {
        echo __CLASS__.'::'.__METHOD__.'('.var_export($id).')'.PHP_EOL;

        return null;
    }

    /**
     * @param $_ids
     *
     * @return \cmd[]
     * @throws \Exception
     */
    public function findByIds($_ids)
    {
        echo __CLASS__.'::'.__METHOD__.'('.var_export($_ids).')'.PHP_EOL;

        return [];
    }

    /**
     * @return \cmd[]
     * @throws \Exception
     */
    public function all()
    {
        echo __CLASS__.'::'.__METHOD__.'()'.PHP_EOL;

        return [];
    }

    /**
     * @return \cmd[]
     * @throws \Exception
     */
    public function allHistoryCmd()
    {
        echo __CLASS__.'::'.__METHOD__.'()'.PHP_EOL;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqLogic_id).', '
            .var_export($_type).', '
            .var_export($_visible).', '
            .var_export($_eqLogic).', '
            .var_export($_has_generic_type).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_logical_id).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_generic_type).', '
            .var_export($_eqLogic_id).', '
            .var_export($_one).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_configuration).', '
            .var_export($_eqType).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqLogic_id).', '
            .var_export($_configuration).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_template).', '
            .var_export($_eqType).', '
            .var_export($_type).', '
            .var_export($_subtype).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqLogic_id).', '
            .var_export($_logicalId).', '
            .var_export($_multiple).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqLogic_id).', '
            .var_export($_generic_type).', '
            .var_export($_multiple).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_value).', '
            .var_export($_type).', '
            .var_export($_onlyEnable).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqType_name).', '
            .var_export($_eqLogic_name).', '
            .var_export($_cmd_name).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_eqLogic_id).', '
            .var_export($_cmd_name).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_name).', '
            .var_export($_eqLogic_name).', '
            .var_export($_cmd_name).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_name).', '
            .var_export($_cmd_name).')'.PHP_EOL
        ;

        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_type).', '
            .var_export($_subType).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * @param \cmd $command
     *
     * @return void
     */
    public function save(\cmd $command)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($command).')'.PHP_EOL
        ;
    }
}

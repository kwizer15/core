<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioRepository;

class InMemoryScenarioRepository implements ScenarioRepository
{
    public function get($_id)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_id).')'.PHP_EOL
        ;

        return [];
    }

    public function findByString($_string)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_string).')'.PHP_EOL
        ;

        return [];
    }

    /**
     * Renvoie tous les objets scenario
     *
     * @param string $_group
     * @param null $_type
     *
     * @return \scenario[]
     */
    public function all($_group = '', $_type = null)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_group).', '
            .var_export($_type).')'.PHP_EOL
        ;

        return [];
    }

    /**
     *
     * @return \scenario[]
     */
    public function schedule()
    {
        echo __CLASS__.'::'.__METHOD__.'()'.PHP_EOL;

        return [];
    }

    /**
     *
     * @param type $_cmd_id
     * @param bool $_onlyEnable
     *
     * @return \scenario[]
     */
    public function findByTrigger($_cmd_id, $_onlyEnable = true)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_cmd_id).', '
            .var_export($_onlyEnable).')'.PHP_EOL
        ;
        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_element_id).')'.PHP_EOL
        ;
        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_id).', '
            .var_export($_onlyEnable).', '
            .var_export($_onlyVisible).')'.PHP_EOL
        ;
        return [];
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
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($_object_name).', '
            .var_export($_group_name).', '
            .var_export($_scenario_name).')'.PHP_EOL
        ;
        return [];
    }

    /**
     *
     * @param array $searchs
     * @return \scenario[]
     */
    public static function searchByUse($searchs)
    {
        echo __CLASS__.'::'.__METHOD__.'('
            .var_export($searchs).')'.PHP_EOL
        ;
        return [];
    }

    /**
     * @return int
     * @throws \ReflectionException
     */
    public function count()
    {
        echo __CLASS__.'::'.__METHOD__.'()'.PHP_EOL;
        return 0;
    }
}

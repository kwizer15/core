<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */

use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Infrastructure\Factory\ConfigurationFactory;
use Jeedom\Core\Domain\Repository\CommandRepository;
use Jeedom\Core\Infrastructure\Factory\RepositoryFactory;
use Jeedom\Core\Infrastructure\Factory\ServiceFactory;
use Jeedom\Core\Presenter\ColorConverter;
use Jeedom\Core\Presenter\HumanCommandMap;
use Jeedom\Core\Presenter\Service\WidgetService;

require_once __DIR__ . '/../../core/php/core.inc.php';

class cmd extends \Jeedom\Core\Domain\Entity\Command {
	/*     * *************************Attributs****************************** */

	
	/*     * ***********************Méthodes statiques*************************** */

    public static function byId($_id) {
        if ($_id == '') {
            return;
        }

        return self::getRepository()->get($_id);
    }

    public static function byIds($_ids) {
        if (!is_array($_ids) || count($_ids) === 0) {
            return;
        }

        return self::getRepository()->findByIds($_ids);
    }

    public static function all() {
        return self::getRepository()->all();
    }

    public static function allHistoryCmd() {
        return self::getRepository()->allHistoryCmd();
    }

    public static function byEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null) {
        return self::getRepository()->findByEqLogicId($_eqLogic_id, $_type, $_visible, $_eqLogic, $_has_generic_type);
    }

    public static function byLogicalId($_logical_id, $_type = null) {
        return self::getRepository()->findByLogicalId($_logical_id, $_type);
    }

    public static function byGenericType($_generic_type, $_eqLogic_id = null, $_one = false) {
        if ($_one) {
            return self::getRepository()->findOneByGenericType($_generic_type, $_eqLogic_id);
        }
        return self::getRepository()->findByGenericType($_generic_type, $_eqLogic_id);
    }

    public static function searchConfiguration($_configuration, $_eqType = null) {
        return self::getRepository()->searchConfiguration($_configuration, $_eqType);
    }

    public static function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null) {
        return self::getRepository()->searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type);
    }

    public static function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null) {
        return self::getRepository()->searchTemplate($_template, $_eqType, $_type, $_subtype);
    }

    public static function byEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false, $_type = null) {
        if ($_multiple) {
            return self::getRepository()->findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type);
        }
        return self::getRepository()->findOneByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type);
    }

    public static function byEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_multiple = false, $_type = null) {
        if ($_multiple) {
            return self::getRepository()->findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type);
        }
        return self::getRepository()->findOneByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type);
    }

    public static function byValue($_value, $_type = null, $_onlyEnable = false) {
        return self::getRepository()->findByValue($_value, $_type, $_onlyEnable);
    }

    public static function byTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name) {
        return self::getRepository()->findOneByTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name);
    }

    public static function byEqLogicIdCmdName($_eqLogic_id, $_cmd_name) {
        return self::getRepository()->findOneByEqLogicIdCmdName($_eqLogic_id, $_cmd_name);
    }

    public static function byObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name) {
        return self::getRepository()->findOneByObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name);
    }

    public static function byObjectNameCmdName($_object_name, $_cmd_name) {
        return self::getRepository()->findOneByObjectNameCmdName($_object_name, $_cmd_name);
    }

    public static function byTypeSubType($_type, $_subType = '') {
        return self::getRepository()->findByTypeSubType($_type, $_subType);
    }

    /**
     * @param $_input
     *
     * @return mixed
     * @throws ReflectionException
     */
	public static function cmdToHumanReadable($_input) {
        return self::getCommandMap()->cmdToHumanReadable($_input);
	}

    /**
     * @param $_input
     *
     * @return array|false|mixed|string
     * @throws ReflectionException
     */
	public static function humanReadableToCmd($_input) {
		return self::getCommandMap()->humanReadableToCmd($_input);
	}

    /**
     * @param string $_string
     *
     * @return \cmd
     * @throws ReflectionException
     */
	public static function byString($_string) {
	    $hashedId = self::getCommandMap()->humanReadableToCmd($_string);
	    $id = str_replace('#', '', $hashedId);
	    $cmd = self::getRepository()->get($id);
        if (!is_object($cmd)) {
            throw new \DomainException(__('La commande n\'a pas pu être trouvée : ', __FILE__) . $_string . __(' => ', __FILE__) . \cmd::humanReadableToCmd($_string));
        }

        return $cmd;
	}

    /**
     * @param $_input
     * @param bool $_quote
     *
     * @return array|mixed
     */
	public static function cmdToValue($_input, $_quote = false) {
		return self::getCommandMap()->cmdToValue($_input, $_quote);
	}

    /**
     * @return array
     * @throws Exception
     */
	public static function allType() {
        return self::getRepository()->listTypes();
	}

    /**
     * @deprecated Use RepositoryFactory::build(CommandRepository::class)->add($cmd) instead
     */
    public function save(): bool {
        trigger_error(__CLASS__ . ':' . __METHOD__ . ' is deprecated. Use CommandRepository::add() instead.', E_USER_DEPRECATED);
        RepositoryFactory::build(CommandRepository::class)->add($this);

        return true;
    }

    /**
     * @deprecated Use RepositoryFactory::build(CommandRepository::class)->refresh($cmd) instead
     */
    public function refresh() {
        trigger_error(__CLASS__ . ':' . __METHOD__ . ' is deprecated. Use CommandRepository::refresh() instead.', E_USER_DEPRECATED);
        RepositoryFactory::build(CommandRepository::class)->refresh($this);
    }

    /**
     * @deprecated Use RepositoryFactory::build(CommandRepository::class)->remove($cmd) instead
     */
    public function remove(): bool {
        trigger_error(__CLASS__ . ':' . __METHOD__ . ' is deprecated. Use CommandRepository::remove() instead.', E_USER_DEPRECATED);
        try {
            RepositoryFactory::build(CommandRepository::class)->remove($this);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $_type
     *
     * @return array
     * @throws Exception
     */
	public static function allSubType($_type = '') {
        return self::getRepository()->listSubTypes($_type);
	}

    /**
     * @return array
     * @throws Exception
     */
	public static function allUnite() {
        return self::getRepository()->listUnites();
	}

    /**
     * @param $_color
     *
     * @return mixed
     * @throws Exception
     */
	public static function convertColor($_color) {
	    return self::getColorConverter()->convert($_color);
	}
	
	public static function availableWidget($_version) {
	    return self::getWidgetService()->getAvailables('cmd', $_version);
	}
	
	public static function returnState($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (is_object($cmd)) {
			$cmd->event($cmd->getConfiguration('returnStateValue', 0));
		}
	}
	
	public static function deadCmd() {
		$return = [];
        $tempActionCmds = [];
        $configs = [
            'actionCheckCmd',
            'jeedomPostExecCmd',
            'jeedomPreExecCmd'
        ];
		foreach (self::getRepository()->all() as $cmd) {
		    foreach ($configs as $config) {
                $actionCommand = $cmd->getConfiguration($config, '');
                if (is_array($actionCommand)) {
                    $tempActionCmds[] = $actionCommand;
                }
            }

            $actionCmds = array_merge(...$tempActionCmds);
            foreach ($actionCmds as $actionCmd) {
                if ($actionCmd['cmd'] != ''
                    && strpos($actionCmd['cmd'], '#') !== false
                    && !self::getRepository()->get(str_replace('#', '', $actionCmd['cmd']))
                ) {
                    $return[] = [
                        'detail' => 'Commande ' . $cmd->getName() . ' de ' . $cmd->getEqLogic()->getName() . ' (' . $cmd->getEqLogic()->getEqType_name() . ')',
                        'help' => 'Action sur valeur', 'who' => $actionCmd['cmd']
                    ];
                }
            }
		}
		return $return;
	}
	
	public static function cmdAlert($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		$value = $cmd->execCmd();
		$check = jeedom::evaluateExpression($value . $cmd->getConfiguration('jeedomCheckCmdOperator') . $cmd->getConfiguration('jeedomCheckCmdTest'));
		if ($check == 1 || $check || $check == '1') {
			$cmd->executeAlertCmdAction();
		}
	}
	
	public static function timelineDisplay($_event) {
		$return = array();
		$return['date'] = $_event['datetime'];
		$return['type'] = $_event['type'];
		$return['group'] = $_event['subtype'];
		$cmd = self::getRepository()->get($_event['id']);
		if (!is_object($cmd)) {
			return null;
		}
		$eqLogic = $cmd->getEqLogic();
		$object = $eqLogic->getObject();
		$return['object'] = is_object($object) ? $object->getId() : 'aucun';
		$return['plugins'] = $eqLogic->getEqType_name();
		$return['category'] = $eqLogic->getCategory();
		
		if ($_event['subtype'] == 'action') {
			$return['html'] = '<div class="cmd" data-id="' . $_event['id'] . '">'
			. '<div style="background-color:#F5A9BC;padding:1px;font-size:0.9em;font-weight: bold;cursor:help;">' . $_event['name'] . '<i class="fa fa-cogs pull-right cursor bt_configureCmd"></i></div>'
			. '<div style="background-color:white;padding:1px;font-size:0.8em;cursor:default;">' . $_event['options'] . '<div/>'
			. '</div>';
		} else {
			$backgroundColor = '#A9D0F5';
			if (isset($_event['cmdType']) && $_event['cmdType'] == 'binary') {
				$backgroundColor = ($_event['value'] == 0 ? '#ff8693' : '#c1e5bd');
			}
			$return['html'] = '<div class="cmd" data-id="' . $_event['id'] . '">'
			. '<div style="background-color:' . $backgroundColor . ';padding:1px;font-size:0.9em;font-weight: bold;cursor:help;">' . $_event['name'] . '<i class="fa fa-cogs pull-right cursor bt_configureCmd"></i></div>'
			. '<div style="background-color:white;padding:1px;font-size:0.8em;cursor:default;">' . $_event['value'] . '<div/>'
			. '</div>';
		}
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */

	
	public static function duringAlertLevel($_options) {
		$cmd = self::getRepository()->get($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		if ($cmd->getType() != 'info') {
			return;
		}
		$value = $cmd->execCmd();
		$level = $cmd->checkAlertLevel($value, false);
		if ($level != 'none') {
			$cmd->actionAlertLevel($level, $value);
		}
	}

    /** **************************** Dépendances ************************************** */

    /**
     * @return CommandRepository
     */
    private static function getRepository()
    {
        return RepositoryFactory::build(CommandRepository::class);
    }

    /**
     * @param $plugin
     *
     * @return Configuration
     */
    private static function getConfig($plugin = 'core')
    {
        return ConfigurationFactory::build($plugin);
    }

    /**
     *
     */
    private static function getCommandMap()
    {
        return ServiceFactory::build(HumanCommandMap::class);
    }

    private static function getColorConverter() {
        return ServiceFactory::build(ColorConverter::class);
    }

    private static function getWidgetService()
    {
        return ServiceFactory::build(WidgetService::class);
    }
}

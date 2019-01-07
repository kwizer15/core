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

use Jeedom\Core\Infrastructure\Configuration\ConfigurationFactory;
use Jeedom\Core\Infrastructure\Event\Configured;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__ . '/../../core/php/core.inc.php';

class config {

    private static $configurations = [];

    private static $eventDispatcher;

    public static function getConfiguration($plugin)
    {
        if (null === self::$eventDispatcher) {
            self::$eventDispatcher = new EventDispatcher();
            self::$eventDispatcher->addListener('preConfig', [self::class, 'listenConfigure']);
            self::$eventDispatcher->addListener('postConfig', [self::class, 'listenConfigure']);
        }
        if (!isset(self::$configurations[$plugin])) {
            self::$configurations[$plugin] = ConfigurationFactory::build($plugin, self::$eventDispatcher);
        }

        return self::$configurations[$plugin];
    }

    /**
     * @deprecated Death code
     */
	public static function getDefaultConfiguration($plugin = 'core')
    {
        return self::getConfiguration($plugin)->multiGet('*');
	}

	/**
	 * Ajoute une clef à la config
	 * @param string $_key
	 * @param string | object | array $_value
	 * @param string $_plugin
	 * @return boolean
	 */
	public static function save($key, $value, $plugin = 'core')
    {
        return self::getConfiguration($plugin)->set($key, $value);
	}

	/**
	 * Supprime une clef de la config
	 * @param string $_key nom de la clef à supprimer
	 * @return boolean vrai si ok faux sinon
	 */
	public static function remove($key, $plugin = 'core')
    {
	    return self::getConfiguration($plugin)->remove($key);
	}

	/**
	 * Retourne la valeur d'une clef
	 * @param string $_key nom de la clef dont on veut la valeur
	 * @return string valeur de la clef
	 */
	public static function byKey($key, $plugin = 'core', $default = '', $forceFresh = false)
    {
	    return self::getConfiguration($plugin)->get($key, $default);
	}

	public static function byKeys($keys, $plugin = 'core', $default = '')
    {
        return self::getConfiguration($plugin)->multiGet($keys, $default);
	}

	public static function searchKey($key, $plugin = 'core') {
        return self::getConfiguration($plugin)->search($key);
	}

	public static function genKey($_car = 32) {
		$key = '';
		$chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $_car; $i++) {
			if (function_exists('random_int')) {
				$key .= $chaine[random_int(0, strlen($chaine) - 1)];
			} else {
				$key .= $chaine[rand(0, strlen($chaine) - 1)];
			}
		}
		return $key;
	}

	public static function getPluginEnable() {
		$sql = 'SELECT `value`,`plugin`
                FROM config
                WHERE `key`=\'active\'';
		$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($values as $value) {
			$return[$value['plugin']] = $value['value'];
		}
		return $return;
	}

	public static function getLogLevelPlugin() {
		$sql = 'SELECT `value`,`key`
                FROM config
                WHERE `key` LIKE \'log::level::%\'';
		$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($values as $value) {
			$return[$value['key']] = is_json($value['value'], $value['value']);
		}
		return $return;
	}

	/*     * *********************Action sur config************************* */

	public static function postConfig_market_allowDNS($_value) {
		if ($_value == 1) {
			if (!network::dns_run()) {
				network::dns_start();
			}
		} else {
			if (network::dns_run()) {
				network::dns_stop();
			}
		}
	}

	public static function preConfig_market_password($_value) {
		if (!is_sha1($_value)) {
			return sha1($_value);
		}
		return $_value;
	}

    public static function listenConfigure(Configured $event, $eventName)
    {
        $plugin = $event->getPlugin();
        $class = ('core' === $plugin) ? 'config' : $plugin;
        $method = $eventName . '_' . str_replace(['::', ':'], '_', $event->getKey());
        if (method_exists($class, $method)) {
            return $event->withValue($class::$method($event->getValue()));
        }

        return $event;
    }
}

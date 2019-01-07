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

/* ------------------------------------------------------------ Inclusions */
require_once __DIR__ . '/../../core/php/core.inc.php';

class com_shell
{
    /**
     * @var \com_shell
     */
	private static $instance;

    /**
     * @var array
     */
	private $cmds = [];

    /**
     * @var bool
     */
	private $background;

    /**
     * @var array
     */
	private $cache = [];

    /**
     * @var array
     */
	private $history = [];

	/*     * ********************Functions static********************* */

	/**
	 * @param string $_cmd
	 * @param bool $_background
	 */
	public function __construct($_cmd = null, $_background = false)
    {
		$this->setBackground($_background);
		if ($_cmd !== null) {
			$this->addCmd($_cmd);
		}
	}

	/**
	 * Get the instance of com_shell
	 * @return com_shell
	 */
	public static function getInstance()
    {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    /**
     * Execute a command
     *
     * @param string $_cmd
     * @param bool $_background
     *
     * @return string
     * @throws Exception
     */
	public static function execute($_cmd, $_background = false)
    {
		$shell = self::getInstance();
		$shell->clear();
		$shell->addCmd($_cmd, $_background);
		return $shell->exec();
	}

	/**
	 * Test if a command exists
	 * @param string $_cmd
	 * @return boolean
	 */
	public static function commandExists($_cmd)
    {
        try {
            self::execute('which '. $_cmd);
        } catch ( \Exception $e ) {
            return false;
        }
        return true;
	}

	/*     * ************* Functions ************************************ */

	/**
	 * Execute commands
	 * @throws Exception
	 * @return string
	 */
	public function exec()
    {
		$output = array();
		$retval = 0;
		$return = array();
		foreach ($this->cmds as $cmd) {
			if (strpos($cmd, '2>&1') === false) {
				$cmd .= ' 2>&1';
			}
			exec($cmd, $output, $retval);
			$return[] = implode("\n", $output);
			if ($retval != 0) {
				throw new Exception(
				    'Erreur dans l\'exécution du terminal, la valeur retournée est : ' . $retval
                    . '. Détails : ' . implode("\n", $output)
                );
			}
			$this->history[] = $cmd;
		}
		$this->cmds = $this->cache;
		$this->cache = [];

		return implode(PHP_EOL, $return);
	}

	/**
	 * @deprecated Replaced by com_shell::commandExists
	 * @param string $_cmd
	 * @return boolean
	 */
	public function commandExist($_cmd)
    {
		return self::commandExists($_cmd);
	}

	public function clear()
    {
		$this->cache = array_merge($this->cache, $this->cmds);
		$this->cmds = [];
	}

	public function clearHistory()
    {
		$this->history = [];
	}

    /**
     * @return string
     */
	public function getCmd()
    {
		return implode(PHP_EOL, $this->cmds);
	}

    /**
     * @param string $_cmd
     * @param bool $_background
     *
     * @return bool
     */
	public function addCmd($_cmd, $_background = null)
    {
		$bg = ($_background === null) ? $this->getBackground() : $_background;
		$add = $bg ? ' >> /dev/null 2>&1 &' : '';
		$this->cmds[] = $_cmd . $add;
		return true;
	}

    /**
     * @param bool $background
     *
     * @return $this
     */
	public function setBackground($background)
    {
		$this->background = $background;
		return $this;
	}

    /**
     * @return bool
     */
	public function getBackground()
    {
		return $this->background;
	}

	/**
	 * Get the history of commands
	 * @return array
	 */
	public function getHistory()
    {
		return $this->history;
	}
}

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

namespace Tests;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;

class logTest extends TestCase
{
    protected function setUp()
    {
        // TODO: Mocker la dépendence à la base de donnée
        try {
            \DB::getConnection();
        } catch (\PDOException $e) {
            $this->markTestSkipped(
                "Connection à la base de donnée non disponible. [{$e->getMessage()}]"
            );
        }
    }

	public function provideEngins()
    {
		return [
			['StreamHandler', StreamHandler::class],
			['foo', StreamHandler::class],
        ];
	}

	public function provideLogs()
    {
		return [
			['StreamHandler', 'foo', false, true],
        ];
	}

	public function provideReturnList()
    {
		return [
			['StreamHandler', []],
        ];
	}

	public function getLevels()
    {
		return [
			['StreamHandler', 'debug'],
			['StreamHandler', 'info'],
			['StreamHandler', 'notice'],
			['StreamHandler', 'warning'],
			['StreamHandler', 'error'],
        ];
	}

	public function provideErrorReporting() {
		return [
			[Logger::DEBUG, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
			[Logger::INFO, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
			[Logger::NOTICE, E_ERROR | E_WARNING | E_PARSE | E_NOTICE],
			[Logger::WARNING, E_ERROR | E_WARNING | E_PARSE],
			[Logger::ERROR, E_ERROR | E_PARSE],
			[Logger::CRITICAL, E_ERROR | E_PARSE],
			[Logger::ALERT, E_ERROR | E_PARSE],
			[Logger::EMERGENCY, E_ERROR | E_PARSE],
        ];
	}

	/**
	 * @dataProvider provideEngins
	 * @param string $name
	 * @param string $instance
	 */
	public function testLoggerHandler($name, $instance)
    {
		\config::save('log::engine', $name);
		$logger = \log::getLogger($name);
		$this->assertInstanceOf(Logger::class, $logger);
		$handler = $logger->popHandler();
		$this->assertInstanceOf($instance, $handler);
	}

	/**
	 * @dataProvider provideLogs
	 * @param string $engin
	 * @param string $message
	 * @param string $get
	 * @param string $removeAll
	 */
	public function testAddGetRemove($engin, $message, $get, $removeAll)
    {
		\config::save('log::engine', $engin);
		\log::remove($engin);
		\log::add($engin, 'debug', $message); // <- Effet de bord!
		$this->assertSame($get, \log::get($engin, 0, 1));
		$this->assertSame($removeAll, log::removeAll());
	}

	/**
	 * @dataProvider getLevels
	 * @param string $engin
	 * @param string $level
	 */
	public function testAddLevels($engin, $level)
    {
		\config::save('log::engine', $engin);
		\log::remove($engin);
		\log::add($engin, $level, 'testLevel');
	}

	/**
	 * @dataProvider provideReturnList
	 * @param string $engin
	 * @param string $return
	 */
	public function testList($engin, $return)
    {
		\config::save('log::engine', $engin);
		\log::add($engin, 'debug', 'toto');
		$this->assertSame($return, \log::liste());
	}

    /**
     * @dataProvider provideErrorReporting
     *
     * @param int $level
     * @param int $result
     *
     * @throws \Exception
     */
	public function testErrorReporting($level, $result)
    {
		$this->assertNull(\log::define_error_reporting($level));
		$this->assertSame($result, error_reporting());
	}
}

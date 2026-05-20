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

use PHPUnit\Framework\TestCase;

class UnserializeSecurityGadget {
	/** @var bool */
	public static $wakeupCalled = false;
	/** @var string */
	public $payload = 'attack';

	public function __wakeup(): void {
		self::$wakeupCalled = true;
	}
}

class unserializeSecurityTest extends TestCase {

	protected function setUp(): void {
		UnserializeSecurityGadget::$wakeupCalled = false;
		class_exists('cache', true);
	}

	public function testMariadbCacheRejectsArbitraryClass(): void {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$key = 'utest_mariadb_' . bin2hex(random_bytes(4));
		$payload = serialize(new UnserializeSecurityGadget());
		DB::Prepare(
			'REPLACE INTO cache SET `key`=:k, `value`=:v, `timestamp`=UNIX_TIMESTAMP(), `lifetime`=60',
			['k' => $key, 'v' => $payload],
			DB::FETCH_TYPE_ROW
		);
		try {
			UnserializeSecurityGadget::$wakeupCalled = false;
			$entry = MariadbCache::fetch($key);
			$this->assertInstanceOf(cache::class, $entry);
			$this->assertFalse(UnserializeSecurityGadget::$wakeupCalled, '__wakeup must not fire');
			$this->assertNotInstanceOf(UnserializeSecurityGadget::class, $entry->getValue());
		} finally {
			DB::Prepare('DELETE FROM cache WHERE `key`=:k', ['k' => $key], DB::FETCH_TYPE_ROW);
		}
	}

	public function testMariadbCacheAllIgnoresArbitraryClass(): void {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$key = 'utest_mariadb_all_' . bin2hex(random_bytes(4));
		$payload = serialize(new UnserializeSecurityGadget());
		DB::Prepare(
			'REPLACE INTO cache SET `key`=:k, `value`=:v, `timestamp`=UNIX_TIMESTAMP(), `lifetime`=60',
			['k' => $key, 'v' => $payload],
			DB::FETCH_TYPE_ROW
		);
		try {
			UnserializeSecurityGadget::$wakeupCalled = false;
			$entries = MariadbCache::all();
			$this->assertFalse(UnserializeSecurityGadget::$wakeupCalled, '__wakeup must not fire');
			foreach ($entries as $entry) {
				$this->assertNotInstanceOf(UnserializeSecurityGadget::class, $entry->getValue());
			}
		} finally {
			DB::Prepare('DELETE FROM cache WHERE `key`=:k', ['k' => $key], DB::FETCH_TYPE_ROW);
		}
	}

	public function testFileCacheRejectsArbitraryClass(): void {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$key = 'utest_file_' . bin2hex(random_bytes(4));
		$entry = new cache();
		$entry->setKey($key);
		$entry->setValue(new UnserializeSecurityGadget());
		$entry->setLifetime(60);
		$entry->setTimestamp(time());
		FileCache::save($entry);
		try {
			UnserializeSecurityGadget::$wakeupCalled = false;
			$loaded = FileCache::fetch($key);
			$this->assertInstanceOf(cache::class, $loaded);
			$this->assertFalse(UnserializeSecurityGadget::$wakeupCalled, '__wakeup must not fire');
			$this->assertNotInstanceOf(UnserializeSecurityGadget::class, $loaded->getValue());
		} finally {
			FileCache::delete($key);
		}
	}

	public function testQueueRejectsArbitraryClass(): void {
		echo "\n" . __CLASS__ . '::' . __FUNCTION__ . ' : ';
		$payload = serialize(['arg1' => new UnserializeSecurityGadget()]);
		DB::Prepare(
			'INSERT INTO queue SET `class`=:c, `function`=:f, `arguments`=:a, `createTime`=NOW()',
			['c' => 'stdClass', 'f' => 'nothing', 'a' => $payload],
			DB::FETCH_TYPE_ROW
		);
		$id = (int)DB::getLastInsertId();
		try {
			UnserializeSecurityGadget::$wakeupCalled = false;
			$queue = queue::byId($id);
			$this->assertInstanceOf(queue::class, $queue);
			$queue->initialize();
			$this->assertFalse(UnserializeSecurityGadget::$wakeupCalled, '__wakeup must not fire');

			$refl = new ReflectionClass($queue);
			$prop = $refl->getProperty('_arrayArguments');
			$prop->setAccessible(true);
			$args = $prop->getValue($queue);
			$this->assertIsArray($args);
			foreach ($args as $arg) {
				$this->assertNotInstanceOf(UnserializeSecurityGadget::class, $arg);
			}
		} finally {
			DB::Prepare('DELETE FROM queue WHERE id=:id', ['id' => $id], DB::FETCH_TYPE_ROW);
		}
	}
}

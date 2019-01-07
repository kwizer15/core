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

use PHPUnit\Framework\TestCase;

class scenarioTest extends TestCase
{
    protected function setUp()
    {
        try {
            \DB::getConnection();
        } catch (\PDOException $e) {
            $this->markTestSkipped(
                "Connection à la base de donnée non disponible. [{$e->getMessage()}]"
            );
        }
    }

	public function provideGetSets()
    {
		return [
			['Id', 'foo', 'foo'],
			['Name', 'foo', 'foo'],
			['State', 'foo', 'foo'],
			['IsActive', true, true],
			['Group', 'foo', 'foo'],
			['LastLaunch', 'foo', 'foo'],
			['Type', 'foo', 'foo'],
			['Mode', 'foo', 'foo'],
			['Schedule', ['foo' => 'bar'], ['foo' => 'bar']],
			['Schedule', '{"foo":"bar"}', ['foo' => 'bar']],
			['Schedule', 'foo', 'foo'],
			['PID', 1, 1],
			['ScenarioElement', ['foo' => 'bar'], ['foo' => 'bar']],
			['ScenarioElement', '{"foo":"bar"}', ['foo' => 'bar']],
			['ScenarioElement', 'foo', 'foo'],
			['Trigger', ['foo' => 'bar'], ['foo' => 'bar']],
			['Trigger', '{"foo":"bar"}', ['foo' => 'bar']],
			['Trigger', 'foo', ['foo']],
			['Timeout', '', null],
			['Timeout', 'foo', null],
			['Timeout', 0.9, null],
			['Timeout', 1.1, 1.1],
			['Timeout', 15, 15],
			['Object_id', null, null],
			['Object_id', ['foo'], null],
			['Object_id', 0, null],
			['Object_id', 150, 150],
			['IsVisible', true, 0],
			['IsVisible', 5, 5],
			['IsVisible', 'foo', 0],
			['Description', 'foo', 'foo'],
			['RealTrigger', 'foo', 'foo'],
        ];
	}

	/**
	 * @dataProvider provideGetSets
	 * @param string $attribute
	 * @param mixed $in
	 * @param mixed $out
	 */
	public function testGetterSetter($attribute, $in, $out) {
		$scenario = new \scenario();
		$getter = 'get' . $attribute;
		$setter = 'set' . $attribute;
		$scenario->$setter($in);
		$this->assertSame($out, $scenario->$getter());
	}

	public function testPersistLog() {
		$path = __DIR__ . '/../../log/scenarioLog/scenarioTest.log';
		if (file_exists($path)) {
			$this->markTestSkipped('Le fichier "' . $path . '" existe déjà. Veuillez le supprimer.');
		}
		$scenario = new \scenario();
		$scenario->setId('Test');
		$scenario->persistLog();
		$this->assertFileExists($path);
		shell_exec('rm ' . $path);
	}
}

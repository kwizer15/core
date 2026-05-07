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

class utilsTest extends TestCase {
	public function getTemplates() {
		return array(
			array('Vous êtes sur {{Nom}} version {{Version}}', 'Vous êtes sur Jeedom version 1.2.3'),
			array('{{La poule}} {{pond}}', 'L\'oeuf est pondu'),
		);
	}
	
	/**
	* @dataProvider getTemplates
	*/
	public function testTemplace_replace($template, $out) {
		$rules = array(
			'{{Nom}}' => 'Jeedom',
			'{{Version}}' => '1.2.3',
			'{{La poule}}' => 'L\'oeuf',
			'{{pond}}' => 'est pondu',
		);
		$result = template_replace($rules, $template);
		$this->assertSame($out, $result);
	}
	
	public function testInit() {
		$_GET['get'] = 'foo';
		$_POST['post'] = 'bar';
		$_REQUEST['request'] = 'baz';
		$this->assertSame('foo', init('get'));
		$this->assertSame('bar', init('post'));
		$this->assertSame('baz', init('request'));
		$this->assertSame('foobar', init('default','foobar'));
	}
	
	public function getTimes() {
		return array(
			array(0, '0s'),
			array(60, '1min 0s'),
			array(65, '1min 5s'),
			array(186, '3min 6s'),
			array(3600, '1h 0min 0s'),
			array(86400, '1j 0h 0min 0s'),
			array(86401, '1j 0h 0min 1s'),
			array(259199, '2j 23h 59min 59s'),
		);
	}
	
	/**
	* @dataProvider getTimes
	*/
	public function testConvertDuartion($in, $out) {
		$this->assertSame($out, convertDuration($in));
	}
	
	public function getJsons() {
		return array(
			array(json_encode(array('foo','bar')), true),
			array(json_encode(array('foo'=>'bar')), true),
			array('{"foo":"bar"}', true),
			array('foo bar', false),
		);
	}
	
	/**
	* @dataProvider getJsons
	*/
	public function testIs_json($in, $out) {
		$this->assertSame($out, is_json($in));
	}
	
	public function getPaths() {
		return array(
			array('/home/user/doc/../../me/docs', '/home/me/docs'),
		);
	}
	
	/**
	* @dataProvider getPaths
	*/
	public function testCleanPath($in, $out) {
		$this->assertSame($out, cleanPath($in));
	}

	private function rootPath() {
		return realpath(__DIR__ . '/../../');
	}

	public function testResolvePathReturnsAbsoluteForRelativeUnderRoot() {
		$result = resolvePath('core/php/utils.inc.php');
		$this->assertSame($this->rootPath() . '/core/php/utils.inc.php', $result);
	}

	public function testResolvePathResolvesDotDotThatStaysUnderRoot() {
		$result = resolvePath('core/php/../class');
		$this->assertSame($this->rootPath() . '/core/class', $result);
	}

	public function testResolvePathRejectsAbsoluteOutsideRoot() {
		$this->assertFalse(resolvePath('/etc/passwd'));
	}

	public function testResolvePathRejectsTraversalOutsideRoot() {
		$this->assertFalse(resolvePath('../../../../../../etc/passwd'));
	}

	public function testResolvePathAcceptsCreationCandidateUnderRoot() {
		$result = resolvePath('core/php/__nonexistent_test_file__.php');
		$this->assertSame($this->rootPath() . '/core/php/__nonexistent_test_file__.php', $result);
	}

	public function testResolvePathRejectsCreationCandidateOutsideRoot() {
		$this->assertFalse(resolvePath('/tmp/__nonexistent_test_file__.php'));
	}

	public function testResolvePathUnsafeReturnsAbsoluteAsIs() {
		$this->assertSame('/etc/passwd', resolvePathUnsafe('/etc/passwd'));
	}

	public function testResolvePathUnsafePrefixesRelative() {
		$result = resolvePathUnsafe('foo.txt');
		$this->assertStringEndsWith('/foo.txt', $result);
	}

	public function testCalculPathLegitimateUnderRootDoesNotTriggerDeprecation() {
		$errors = [];
		set_error_handler(function ($errno, $errstr) use (&$errors) {
			$errors[] = [$errno, $errstr];
		}, E_USER_DEPRECATED);
		try {
			$result = calculPath('core/php/utils.inc.php');
		} finally {
			restore_error_handler();
		}
		$this->assertSame($this->rootPath() . '/core/php/utils.inc.php', $result);
		$this->assertSame([], $errors, 'No deprecation should be raised when path resolves under the webroot');
	}

	public function testCalculPathOutsideRootTriggersDeprecation() {
		$errors = [];
		set_error_handler(function ($errno, $errstr) use (&$errors) {
			$errors[] = [$errno, $errstr];
		}, E_USER_DEPRECATED);
		try {
			$result = calculPath('/etc/passwd');
		} finally {
			restore_error_handler();
		}
		$this->assertSame('/etc/passwd', $result, 'Backward-compatible fallback returns the unsafe resolution');
		$this->assertCount(1, $errors, 'A deprecation notice must be emitted');
		$this->assertSame(E_USER_DEPRECATED, $errors[0][0]);
	}

	public function testCalculPathOutsideRootWithFlagDoesNotTriggerDeprecation() {
		$errors = [];
		set_error_handler(function ($errno, $errstr) use (&$errors) {
			$errors[] = [$errno, $errstr];
		}, E_USER_DEPRECATED);
		try {
			$result = calculPath('/etc/passwd', true);
		} finally {
			restore_error_handler();
		}
		$this->assertSame('/etc/passwd', $result);
		$this->assertSame([], $errors, 'Explicit opt-in must silence the deprecation');
	}
}

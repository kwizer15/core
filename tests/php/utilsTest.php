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

	public function testShellWgetCommandEscapesUrl() {
		$cmd = shellWgetCommand('https://example.com/foo; rm -rf /', '/tmp/out.zip');
		$this->assertStringContainsString("'https://example.com/foo; rm -rf /'", $cmd);
		$this->assertStringNotContainsString('https://example.com/foo; rm -rf / -O', $cmd);
		$this->assertStringEndsWith("-O '/tmp/out.zip'", $cmd);
	}

	public function testShellWgetCommandEscapesOutputPath() {
		$cmd = shellWgetCommand('https://example.com', '/tmp/$(rm -rf /).zip');
		$this->assertStringContainsString("'/tmp/\$(rm -rf /).zip'", $cmd);
	}

	public function testShellWgetCommandWithLogPath() {
		$cmd = shellWgetCommand('https://example.com', '/tmp/out', '/tmp/log; evil');
		$this->assertStringContainsString("'/tmp/log; evil'", $cmd);
		$this->assertStringEndsWith("2>&1", $cmd);
	}

	public function testShellCurlCommandWithoutToken() {
		$cmd = shellCurlCommand('https://api.github.com/foo', '/tmp/out');
		$this->assertStringNotContainsString('Authorization', $cmd);
		$this->assertStringContainsString("'https://api.github.com/foo'", $cmd);
		$this->assertStringContainsString("> '/tmp/out'", $cmd);
	}

	public function testShellCurlCommandWithToken() {
		$cmd = shellCurlCommand('https://api', '/tmp/out', 'tk; evil');
		$this->assertStringContainsString("'Authorization: token tk; evil'", $cmd);
	}

	public function testShellCurlCommandEscapesMaliciousUrl() {
		$cmd = shellCurlCommand('https://api"; rm -rf /; "', '/tmp/out');
		$this->assertStringNotContainsString('https://api"; rm -rf /; " ', $cmd);
		$this->assertStringContainsString("'https://api\"; rm -rf /; \"'", $cmd);
	}

	public function testShellCheckOngoingThreadCommandEscapesCmd() {
		$cmd = shellCheckOngoingThreadCommand('foo"; evil; "');
		$this->assertStringContainsString("'foo\"; evil; \"\$'", $cmd);
		$this->assertStringEndsWith('| wc -l', $cmd);
	}

	public function testShellRetrievePidThreadCommandEscapesCmd() {
		$cmd = shellRetrievePidThreadCommand('foo"; evil; "');
		$this->assertStringContainsString("'foo\"; evil; \"\$'", $cmd);
		$this->assertStringEndsWith("awk '{print \$1}'", $cmd);
	}
}

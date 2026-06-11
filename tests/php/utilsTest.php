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

	public function testShellMysqlConnectionArgsWithSocket() {
		$args = shellMysqlConnectionArgs('localhost', 3306, 'root', 'pwd', 'mydb', '/var/run/mysqld/mysqld.sock');
		$this->assertStringContainsString("--socket='/var/run/mysqld/mysqld.sock'", $args);
		$this->assertStringNotContainsString('--host', $args);
		$this->assertStringNotContainsString('--port', $args);
	}

	public function testShellMysqlConnectionArgsLocalhostDefaultPort() {
		$args = shellMysqlConnectionArgs('localhost', 3306, 'root', 'pwd', 'mydb');
		$this->assertStringNotContainsString('--host', $args);
		$this->assertStringNotContainsString('--port', $args);
		$this->assertStringContainsString("--user='root'", $args);
		$this->assertStringContainsString("--password='pwd'", $args);
		$this->assertStringEndsWith("'mydb'", $args);
	}

	public function testShellMysqlConnectionArgsRemoteHost() {
		$args = shellMysqlConnectionArgs('db.internal', 33306, 'root', 'pwd', 'mydb');
		$this->assertStringContainsString("--host='db.internal'", $args);
		$this->assertStringContainsString("--port='33306'", $args);
	}

	public function testShellMysqlConnectionArgsEscapesPassword() {
		$args = shellMysqlConnectionArgs('localhost', 3306, 'root', "p; rm -rf /; '", 'mydb');
		$this->assertStringContainsString("--password='" . str_replace("'", "'\\''", "p; rm -rf /; '") . "'", $args);
	}

	public function testShellMysqlConnectionArgsEscapesDbname() {
		$args = shellMysqlConnectionArgs('localhost', 3306, 'root', 'pwd', 'mydb; cat /etc/passwd');
		$this->assertStringEndsWith("'mydb; cat /etc/passwd'", $args);
	}

	public function testShellTarCreateCommandBasic() {
		$cmd = shellTarCreateCommand('/var/www', '/backup/out.tar.gz', []);
		$this->assertStringContainsString("cd '/var/www'", $cmd);
		$this->assertStringContainsString("tar cfz '/backup/out.tar.gz'", $cmd);
		$this->assertStringEndsWith(' .', $cmd);
	}

	public function testShellTarCreateCommandWithExcludes() {
		$cmd = shellTarCreateCommand('/var/www', '/backup/out.tar.gz', ['tmp', './log; evil', 'docs']);
		$this->assertStringContainsString("--exclude='tmp'", $cmd);
		$this->assertStringContainsString("--exclude='./log; evil'", $cmd);
		$this->assertStringContainsString("--exclude='docs'", $cmd);
	}

	public function testShellTarCreateCommandEscapesOutputArchive() {
		$cmd = shellTarCreateCommand('/var/www', '/backup/out"; evil; ".tar.gz', []);
		$this->assertStringContainsString("'/backup/out\"; evil; \".tar.gz'", $cmd);
	}
}

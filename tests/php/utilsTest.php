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

	private function captureSendVarToJS($name, $value) {
		ob_start();
		sendVarToJS($name, $value);
		return ob_get_clean();
	}

	public function testSendVarToJSScalarEscapesClosingScriptTag() {
		$out = $this->captureSendVarToJS('foo', '</script><script>alert(1)</script>');
		$this->assertSame(1, substr_count($out, '<script>'), 'Only one opening <script> tag should be emitted');
		$this->assertSame(1, substr_count($out, '</script>'), 'Only one closing </script> tag should be emitted');
		$this->assertStringNotContainsString('</script><script>alert(1)', $out, 'Raw payload must not appear unescaped');
	}

	public function testSendVarToJSScalarEscapesQuote() {
		$out = $this->captureSendVarToJS('foo', 'bar"; evil=1; //');
		$this->assertStringNotContainsString('"; evil=1', $out, 'Quote must be escaped so the injected statement stays inside the JS string literal');
	}

	public function testSendVarToJSArrayValueEscapesClosingScriptTag() {
		$out = $this->captureSendVarToJS('foo', ['x' => '</script><script>alert(1)</script>']);
		$this->assertSame(1, substr_count($out, '<script>'), 'Only one opening <script> tag should be emitted');
		$this->assertSame(1, substr_count($out, '</script>'), 'Only one closing </script> tag should be emitted');
	}

	public function testSendVarToJSArrayGoesThroughJsonParse() {
		$out = $this->captureSendVarToJS('foo', ['__proto__' => ['polluted' => true]]);
		$this->assertStringContainsString('JSON.parse(', $out, 'Arrays must be wrapped in JSON.parse to keep __proto__ as an own property (object literal would assign prototype)');
	}

	public function testSendVarToJSObjectGoesThroughJsonParse() {
		$obj = new \stdClass();
		$obj->foo = 'bar';
		$out = $this->captureSendVarToJS('myObj', $obj);
		$this->assertStringContainsString('JSON.parse(', $out, 'Objects must be wrapped in JSON.parse like arrays');
		$this->assertStringContainsString('var myObj = JSON.parse(', $out);
	}

	public function testSendVarToJSDottedNameAssigns() {
		$out = $this->captureSendVarToJS('jeephp2js.foo', 'bar');
		$this->assertStringContainsString('jeephp2js.foo = "bar"', $out, 'Dotted names must be emitted without var');
		$this->assertStringNotContainsString('var jeephp2js.foo', $out, 'var keyword would produce a syntax error on a dotted access');
	}

	public function getNonStringScalars() {
		return array(
			array(true, '"1"'),
			array(false, '""'),
			array(null, '""'),
			array(0, '"0"'),
			array(42, '"42"'),
			array(3.14, '"3.14"'),
		);
	}

	/**
	* @dataProvider getNonStringScalars
	*/
	public function testSendVarToJSCastsNonStringScalarsToString($value, $expected) {
		$out = $this->captureSendVarToJS('foo', $value);
		$this->assertStringContainsString('var foo = ' . $expected, $out, 'Non-string scalars must be cast to string for backwards compatibility');
	}

	public function testSendVarToJSEscapesBackslash() {
		$out = $this->captureSendVarToJS('foo', 'a\\b');
		$this->assertStringContainsString('"a\\\\b"', $out, 'Backslash must be escaped so JS does not interpret \\b as backspace');
	}
}

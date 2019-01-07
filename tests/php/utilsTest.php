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

class utilsTest extends TestCase
{
	public function provideTemplates()
    {
		return [
			['Vous êtes sur {{Nom}} version {{Version}}', 'Vous êtes sur Jeedom version 1.2.3'],
			['{{La poule}} {{pond}}', 'L\'oeuf est pondu'],
        ];
	}

    /**
     * @dataProvider provideTemplates
     *
     * @param string $template
     * @param string $expectedOut
     */
	public function testTemplateReplace($template, $expectedOut)
    {
		$rules = array(
			'{{Nom}}' => 'Jeedom',
			'{{Version}}' => '1.2.3',
			'{{La poule}}' => 'L\'oeuf',
			'{{pond}}' => 'est pondu',
		);
		$result = template_replace($rules, $template);
		$this->assertSame($expectedOut, $result);
	}

	public function testInit()
    {
		$_GET['get'] = 'foo';
		$_POST['post'] = 'bar';
		$_REQUEST['request'] = 'baz';
		$this->assertSame('foo', init('get'));
		$this->assertSame('bar', init('post'));
		$this->assertSame('baz', init('request'));
		$this->assertSame('foobar', init('default','foobar'));
	}

	public function provideTimes()
    {
		return [
				[0, '0s'],
				[60, '1min 0s'],
				[65, '1min 5s'],
				[186, '3min 6s'],
				[3600, '1h 0min 0s'],
				[86400, '1j 0h 0min 0s'],
				[86401, '1j 0h 0min 1s'],
				[259199, '2j 23h 59min 59s'],
        ];
	}

    /**
     * @dataProvider provideTimes
     *
     * @param int $in
     * @param string $expectedOut
     */
	public function testConvertDuration($in, $expectedOut)
    {
		$this->assertSame($expectedOut, convertDuration($in));
	}

	public function provideJsons()
    {
		return [
				[json_encode(['foo','bar']), true],
				[json_encode(['foo'=>'bar']), true],
				['{"foo":"bar"}', true],
				['foo bar', false],
        ];
	}

    /**
     * @dataProvider provideJsons
     *
     * @param $in
     * @param $expectedOut
     */
	public function testIs_json($in, $expectedOut)
    {
		$this->assertSame($expectedOut, is_json($in));
	}

	public function providePaths()
    {
		return [
			['/home/user/doc/../../me/docs', '/home/me/docs'],
        ];
	}

    /**
     * @dataProvider providePaths
     *
     * @param $in
     * @param $expectedOut
     */
	public function testCleanPath($in, $expectedOut)
    {
		$this->assertSame($expectedOut, cleanPath($in));
	}
}

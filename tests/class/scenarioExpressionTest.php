<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class scenarioExpressionTest extends TestCase
{
	public function provideExpressions()
	{
		return [
			['1+1', '2']
		];
	}

	/**
	 * @dataProvider provideExpressions
	 *
	 * @param $expressions
	 * @param $expectedResult
	 */
	public function testCalculCondition($expressions, $expectedResult)
	{
		$result = \scenarioExpression::createAndExec('condition', $expressions);
		$this->assertEquals($expectedResult, $result);
	}

	public function testVariable()
	{
		\scenarioExpression::createAndExec('action', 'variable', ['value' => 'plop', 'name' => 'test']);
		$result = \scenarioExpression::createAndExec('condition', 'variable(test)');
		$this->assertEquals('plop', $result);
	}

	/**
	 * @depends testVariable
	 */
	public function testStringCondition()
	{
		$result = \scenarioExpression::createAndExec('condition', 'variable(test) == "plop"');
		$this->assertTrue($result);
	}
}


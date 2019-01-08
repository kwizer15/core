<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class configTest extends TestCase
{
	public function testSave()
    {
        $this->assertEquals('plop', \config::byKey('toto', 'core', 'plop'));

        $this->assertEquals('plop', \config::byKey('toto'));
		\config::save('toto', 'toto');
        $this->assertEquals('toto', \config::byKey('toto'));
        \config::remove('toto');
        $this->assertEquals(null, \config::byKey('toto'));
	}
}

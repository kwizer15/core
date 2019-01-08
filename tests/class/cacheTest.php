<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class cacheTest extends TestCase
{
	public function testSave()
    {
        $this->assertNull('toto', \cache::byKey('toto')->getValue());
		\cache::set('toto', 'toto');
		$cache = \cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
        $cache->remove();
        $this->assertNull('toto', \cache::byKey('toto')->getValue());
	}

	public function testTime() {
		\cache::set('toto', 'toto', 1);
		$cache = \cache::byKey('toto');
		$this->assertEquals('toto', $cache->getValue());
		sleep(2);
		$cache = \cache::byKey('toto');
		$this->assertEquals(null, $cache->getValue());
	}
}

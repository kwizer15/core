<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class configTest extends TestCase
{
    protected function setUp()
    {
        // TODO: Mocker la dépendence à la base de donnée
        try {
            \DB::getConnection();
        } catch (\PDOException $e) {
            $this->markTestSkipped(
                "Connection à la base de donnée non disponible. [{$e->getMessage()}]"
            );
        }
    }

	public function testSave()
    {
        $this->assertEquals('plop', \config::byKey('toto', 'core', 'plop'));

        $this->assertEquals('plop', \config::byKey('toto'));
		\config::save('toto', 'toto');
        $this->assertEquals('toto', \config::byKey('toto'));
        \config::remove('toto');
        $this->assertEquals('plop', \config::byKey('toto'));
	}
}

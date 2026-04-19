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

class networkTrustedProxyTest extends TestCase
{
	public function testEmptyListRejectsEverything()
	{
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', ''));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', '   '));
	}

	public function testInvalidIpRejected()
	{
		$this->assertFalse(network::isFromTrustedProxy('not-an-ip', '10.0.0.5'));
		$this->assertFalse(network::isFromTrustedProxy('', '10.0.0.5'));
	}

	public function testExactIpv4Match()
	{
		$this->assertTrue(network::isFromTrustedProxy('10.0.0.5', '10.0.0.5'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.6', '10.0.0.5'));
	}

	public function testIpv4CidrMatch()
	{
		$this->assertTrue(network::isFromTrustedProxy('192.168.1.42', '192.168.1.0/24'));
		$this->assertTrue(network::isFromTrustedProxy('192.168.1.255', '192.168.1.0/24'));
		$this->assertFalse(network::isFromTrustedProxy('192.168.2.1', '192.168.1.0/24'));
	}

	public function testIpv4CidrNonByteAlignedMask()
	{
		$this->assertTrue(network::isFromTrustedProxy('10.0.0.1', '10.0.0.0/30'));
		$this->assertTrue(network::isFromTrustedProxy('10.0.0.3', '10.0.0.0/30'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.4', '10.0.0.0/30'));
	}

	public function testIpv6ExactMatch()
	{
		$this->assertTrue(network::isFromTrustedProxy('::1', '::1'));
		$this->assertTrue(network::isFromTrustedProxy('2001:db8::1', '2001:db8::1'));
		$this->assertFalse(network::isFromTrustedProxy('2001:db8::2', '2001:db8::1'));
	}

	public function testIpv6CidrMatch()
	{
		$this->assertTrue(network::isFromTrustedProxy('2001:db8::1234', '2001:db8::/32'));
		$this->assertFalse(network::isFromTrustedProxy('2001:db9::1', '2001:db8::/32'));
	}

	public function testCommaSeparatedList()
	{
		$list = '10.0.0.5, 192.168.1.0/24, ::1';
		$this->assertTrue(network::isFromTrustedProxy('10.0.0.5', $list));
		$this->assertTrue(network::isFromTrustedProxy('192.168.1.99', $list));
		$this->assertTrue(network::isFromTrustedProxy('::1', $list));
		$this->assertFalse(network::isFromTrustedProxy('8.8.8.8', $list));
	}

	public function testFamilyMismatchRejected()
	{
		$this->assertFalse(network::isFromTrustedProxy('::1', '127.0.0.1'));
		$this->assertFalse(network::isFromTrustedProxy('127.0.0.1', '::1'));
		$this->assertFalse(network::isFromTrustedProxy('::1', '10.0.0.0/8'));
	}

	public function testInvalidEntriesAreSkipped()
	{
		$this->assertTrue(network::isFromTrustedProxy('10.0.0.5', 'garbage, 10.0.0.5'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', 'garbage, 999.999.999.999'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', '10.0.0.5/abc'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', '10.0.0.5/-1'));
		$this->assertFalse(network::isFromTrustedProxy('10.0.0.5', '10.0.0.5/33'));
	}
}

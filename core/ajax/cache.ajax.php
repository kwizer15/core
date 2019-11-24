<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

class AjaxCacheController implements AjaxController
{
    public function getDefaultAccess()
    {
        return 'admin';
    }

	public function flush() {
		unautorizedInDemo();
		cache::flush();
		return '';
	}
	
	public function flushWidget() {
		unautorizedInDemo();
		cache::flushWidget();
		return '';
	}
	
	public function clean() {
		unautorizedInDemo();
		cache::clean();
		return '';
	}
	
	public function stats() {
		return cache::stats();
	}
}

ajaxHandle(new AjaxCacheController());
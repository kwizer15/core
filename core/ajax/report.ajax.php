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

class AjaxReportController implements AjaxController
{
    public function getDefaultAccess()
    {
        return 'admin';
    }

	public function list() {
		$return = array();
		$path = __DIR__ . '/../../data/report/' . init('type') . '/' . init('id') . '/';
		foreach (ls($path, '*') as $value) {
			$return[$value] = array('name' => $value);
		}
		return $return;
	}

	public function get() {
		$path = __DIR__ . '/../../data/report/' . init('type') . '/' . init('id') . '/' . init('report');
		$return = pathinfo($path);
		$return['path'] = $path;
		$return['type'] = init('type');
		$return['id'] = init('id');
		return $return;
	}

	public function remove() {
		$path = __DIR__ . '/../../data/report/' . init('type') . '/' . init('id') . '/' . init('report');
		if (file_exists($path)) {
			unlink($path);
		}
		if (file_exists($path)) {
			throw new Exception(__('Impossible de supprimer : ', __FILE__) . $path);
		}
		return '';
	}

	public function removeAll() {
		$path = __DIR__ . '/../../data/report/' . init('type') . '/' . init('id') . '/';
		foreach (ls($path, '*') as $value) {
			unlink($path . $value);
		}
		return $return; // FIXME: variable inconnue
	}
}

ajaxHandle(new AjaxReportController());

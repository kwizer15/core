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

class AjaxNoteController implements AjaxController
{
    public function getDefaultAccess()
    {
        return null;
    }

	public function all() {
        ajax::checkAccess('admin');
		return utils::o2a(note::all());
	}

	public function byId() {
        ajax::checkAccess('admin');
		return utils::o2a(note::byId(init('id')));
	}

	public function save() {
        ajax::checkAccess('admin');
		$note_json = json_decode(init('note'), true);
		if (isset($note_json['id'])) {
			$note = note::byId($note_json['id']);
		}
		if (!isset($note) || !is_object($note)) {
			$note = new note();
		}
		utils::a2o($note, $note_json);
		$note->save();
		return utils::o2a($note);
	}

	public function remove() {
        ajax::checkAccess('admin');
		$note = note::byId(init('id'));
		if (!is_object($note)) {
			throw new Exception(__('Note inconnue. Vérifiez l\'ID', __FILE__));
		}
		$note->remove();
		return '';
	}
}

ajaxHandle(new AjaxNoteController());

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

class AjaxMessageController implements AjaxController
{
    public function getDefaultAccess()
    {
        return '';
    }

	public function clearMessage() {
		message::removeAll(init('plugin'));
		return '';
	}

	public function nbMessage() {
		return message::nbMessage();
	}

	public function all() {
		if (init('plugin') == '') {
			$messages = utils::o2a(message::all());
		} else {
			$messages = utils::o2a(message::byPlugin(init('plugin')));
		}
		foreach ($messages as &$message) {
			$message['message'] = htmlentities($message['message']);
		}
		return $messages;
	}

	public function removeMessage() {
		$message = message::byId(init('id'));
		if (!is_object($message)) {
			throw new Exception(__('Message inconnu. Vérifiez l\'ID', __FILE__));
		}
		$message->remove();
		return '';
	}
}

ajaxHandle(new AjaxMessageController());
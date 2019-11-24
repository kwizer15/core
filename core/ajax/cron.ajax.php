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

class AjaxCronController implements AjaxController
{
    public function getDefaultAccess()
    {
        return 'admin';
    }

    public function save() {
		unautorizedInDemo();
		utils::processJsonObject('cron', init('crons'));
		return '';
	}

	public function remove() {
		unautorizedInDemo();
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->remove();
		return '';
	}

	public function all() {
		$crons = cron::all(true);
		foreach ($crons as $cron) {
			$cron->refresh();
		}
		return utils::o2a($crons);
	}

	public function start() {
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->run();
		sleep(1);
		return '';
	}

	public function stop() {
		$cron = cron::byId(init('id'));
		if (!is_object($cron)) {
			throw new Exception(__('Cron id inconnu', __FILE__));
		}
		$cron->halt();
		sleep(1);
		return '';
	}
}

ajaxHandle(new AjaxCronController());
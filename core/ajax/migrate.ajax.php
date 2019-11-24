<?php

/** @entrypoint */
/** @ajax */

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Genxeral Public License as published by
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

class AjaxMigrateController implements AjaxController
{
    public function getDefaultAccess()
    {
        return 'admin';
    }

	public function usbTry() {
		$usbTry = migrate::usbTry();
		return $usbTry;
	}
	
	public function backupToUsb() {
		$backupToUsb = migrate::backupToUsb();
		return $backupToUsb;
	}
	
	public function imageToUsb() {
		$imageToUsb = migrate::imageToUsb();
		return $imageToUsb;
	}
	
	public function freeSpaceUsb() {
		$freeSpaceUsb = migrate::freeSpaceUsb();
		return $freeSpaceUsb;
	}
	
	public function getStep() {
		$valueMigrate = config::byKey('stepMigrate');
		return $valueMigrate;
	}
	
	public function setStep() {
		if(init('stepValues')){
			config::save('stepMigrate', init('stepValues'));
			return init('stepValues');
		}
	}
	public function renameImage() {
		$renameImage = migrate::renameImage();
		return $renameImage;
	}
	public function GoBackupInstall() {
		$GoBackupInstall = migrate::GoBackupInstall();
		return $GoBackupInstall;
	}
	public function finalisation() {
		$finalisation = migrate::finalisation();
		return $finalisation;
	}
}

ajaxHandle(new AjaxMigrateController());
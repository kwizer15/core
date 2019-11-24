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

class AjaxUserController implements AjaxController
{
    public function getDefaultAccess()
    {
        return null;
    }

	public function useTwoFactorAuthentification() {
		$user = user::byLogin(init('login'));
		if (!is_object($user)) {
			return 0;
		}
		if (network::getUserLocation() == 'internal') {
			return 0;
		}
		return $user->getOptions('twoFactorAuthentification', 0);
	}

	public function login() {
		if(!file_exists(session_save_path())){
			try {
				com_shell::execute(system::getCmdSudo() . ' mkdir ' .session_save_path().';'.system::getCmdSudo() . ' chmod 777 -R ' .session_save_path());
			} catch (\Exception $e) {

			}
		}
		try {
			if(com_shell::execute(system::getCmdSudo() . ' ls ' . session_save_path().' | wc -l') > 500){
				com_shell::execute(system::getCmdSudo() .'/usr/lib/php/sessionclean');
			}
		} catch (\Exception $e) {

		}
		if (!isConnect()) {
			if (config::byKey('sso:allowRemoteUser') == 1) {
				$user = user::byLogin($_SERVER['REMOTE_USER']);
				if (is_object($user) && $user->getEnable() == 1) {
					@session_start();
					$_SESSION['user'] = $user;
					@session_write_close();
					log::add('connection', 'info', __('Connexion de l\'utilisateur par REMOTE_USER : ', __FILE__) . $_SESSION['user']->getLogin());
				}
			}
			if (!login(init('username'), init('password'), init('twoFactorCode'))) {
				throw new Exception('Mot de passe ou nom d\'utilisateur incorrect');
			}
		}

		if (init('storeConnection') == 1) {
			$rdk = config::genKey();
			$registerDevice = $_SESSION['user']->getOptions('registerDevice', array());
			if (!is_array($registerDevice)) {
				$registerDevice = array();
			}
			$registerDevice[sha512($rdk)] = array(
				'datetime' => date('Y-m-d H:i:s'),
				'ip' => getClientIp(),
				'session_id' =>session_id(),
			);
			setcookie('registerDevice', $_SESSION['user']->getHash() . '-' . $rdk, time() + 365 * 24 * 3600, "/", '', false, true);
			@session_start();
			$_SESSION['user']->setOptions('registerDevice', $registerDevice);
			$_SESSION['user']->save();
			@session_write_close();
			if (!isset($_COOKIE['jeedom_token'])) {
				setcookie('jeedom_token', ajax::getToken(), time() + 365 * 24 * 3600, "/", '', false, true);
			}
		}
		return '';
	}

	public function getApikey() {
		if (!login(init('username'), init('password'), init('twoFactorCode'))) {
			throw new Exception('Mot de passe ou nom d\'utilisateur incorrect');
		}
		return $_SESSION['user']->getHash();
	}

	public function validateTwoFactorCode() {
        ajax::checkAccess('');
		unautorizedInDemo();
		@session_start();
		$_SESSION['user']->refresh();
		$result = $_SESSION['user']->validateTwoFactorCode(init('code'));
		if ($result && init('enableTwoFactorAuthentification') == 1) {
			$_SESSION['user']->setOptions('twoFactorAuthentification', 1);
			$_SESSION['user']->save();
		}
		@session_write_close();
		return $result;
	}

	public function removeTwoFactorCode() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$user = user::byId(init('id'));
		if (!is_object($user)) {
			throw new Exception('User ID inconnu');
		}
		$user->setOptions('twoFactorAuthentification', 0);
		$user->save();
		return '';
	}

	public function isConnect() {
        ajax::checkAccess('');
		return '';
	}

	public function refresh() {
        ajax::checkAccess('');
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
		return '';
	}

	public function logout() {
        ajax::checkAccess('');
		logout();
		return '';
	}

	public function all() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$users = array();
		foreach (user::all() as $user) {
			$user_info = utils::o2a($user);
			$users[] = $user_info;
		}
		return $users;
	}

	public function save() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$users = json_decode(init('users'), true);
		$user = null;
		foreach ($users as &$user_json) {
			if (isset($user_json['id'])) {
				$user = user::byId($user_json['id']);
			}
			if (!is_object($user)) {
				if (config::byKey('ldap::enable') == '1') {
					throw new Exception(__('Vous devez désactiver l\'authentification LDAP pour pouvoir ajouter un utilisateur', __FILE__));
				}
				$user = new user();
			}
			utils::a2o($user, $user_json);
			$user->save();
		}
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
		return '';
	}

	public function remove() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		if (config::byKey('ldap::enable') == '1') {
			throw new Exception(__('Vous devez désactiver l\'authentification LDAP pour pouvoir supprimer un utilisateur', __FILE__));
		}
		if (init('id') == $_SESSION['user']->getId()) {
			throw new Exception(__('Vous ne pouvez pas supprimer le compte avec lequel vous êtes connecté', __FILE__));
		}
		$user = user::byId(init('id'));
		if (!is_object($user)) {
			throw new Exception('User ID inconnu');
		}
		$user->remove();
		return '';
	}

	public function saveProfils() {
        ajax::checkAccess('');
		unautorizedInDemo();
		$user_json = jeedom::fromHumanReadable(json_decode(init('profils'), true));
		if (isset($user_json['id']) && $user_json['id'] != $_SESSION['user']->getId()) {
			throw new Exception('401 - Accès non autorisé');
		}
		@session_start();
		$_SESSION['user']->refresh();
		$login = $_SESSION['user']->getLogin();
		$rights = $_SESSION['user']->getRights();
		utils::a2o($_SESSION['user'], $user_json);
		foreach ($rights as $right => $value) {
			$_SESSION['user']->setRights($right, $value);
		}
		$_SESSION['user']->setLogin($login);
		$_SESSION['user']->save();
		@session_write_close();
		eqLogic::clearCacheWidget();
		return '';
	}

	public function get() {
        ajax::checkAccess('');
		return jeedom::toHumanReadable(utils::o2a($_SESSION['user']));
	}

	public function removeRegisterDevice() {
        ajax::checkAccess('');
		unautorizedInDemo();
		if (init('key') == '' && init('user_id') == '') {
            ajax::checkAccess('admin');
			foreach (user::all() as $user) {
				if ($user->getId() == $_SESSION['user']->getId()) {
					$_SESSION['user']->setOptions('registerDevice', array());
					$_SESSION['user']->save();
				} else {
					$user->setOptions('registerDevice', array());
					$user->save();
				}
			}
			return '';
		}
		if (init('user_id') != '') {
            ajax::checkAccess('admin');
			$user = user::byId(init('user_id'));
			if (!is_object($user)) {
				throw new Exception(__('Utilisateur non trouvé : ', __FILE__) . init('user_id'));
			}
			$registerDevice = $user->getOptions('registerDevice', array());
		} else {
			$registerDevice = $_SESSION['user']->getOptions('registerDevice', array());
		}

		if (init('key') == '') {
			$registerDevice = array();
		} elseif (isset($registerDevice[init('key')])) {
			unset($registerDevice[init('key')]);
		}
		if (init('user_id') != '') {
			$user->setOptions('registerDevice', $registerDevice);
			$user->save();
		} else {
			@session_start();
			$_SESSION['user']->setOptions('registerDevice', $registerDevice);
			$_SESSION['user']->save();
			@session_write_close();
		}
		return '';
	}

	public function deleteSession() {
        ajax::checkAccess('');
		unautorizedInDemo();
		$sessions = listSession();
		if (isset($sessions[init('id')])) {
			$user = user::byId($sessions[init('id')]['user_id']);
			if (is_object($user)) {
				$registerDevice = $user->getOptions('registerDevice', array());
				foreach ($user->getOptions('registerDevice', array()) as $key => $value) {
					if ($value['session_id'] == init('id')) {
						unset($registerDevice[$key]);
					}
				}
				$user->setOptions('registerDevice', $registerDevice);
				$user->save();
			}
		}
		deleteSession(init('id'));
		return '';
	}

	public function testLdapConnection() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$connection = user::connectToLDAP();
		if ($connection === false) {
			throw new Exception();
		}
		return '';
	}

	public function removeBanIp() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		return user::removeBanIp();
	}

	public function supportAccess() {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		return user::supportAccess(init('enable'));
	}
}

ajaxHandle(new AjaxUserController());

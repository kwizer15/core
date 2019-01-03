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

use Dotenv\Exception\InvalidPathException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require_once __DIR__ . '/vendor/autoload.php';

try {
    (new \Dotenv\Dotenv(__DIR__))->load();
} catch (InvalidPathException $e) {
    \Http\Response\send(new Response(302, ['Location' => 'install/setup.php']));
    exit;
}

try {
    $request = ServerRequest::fromGlobals();
    $queryParams = $request->getQueryParams();

	if (!isset($queryParams['v'])) {
	    $userAgent = $request->hasHeader('HTTP_USER_AGENT')
            ? $request->getHeader('HTTP_USER_AGENT')
            : 'none'
        ;
        $queryParams['v'] = userAgentIsMobile($userAgent) ? 'm' : 'd';
        $url = $request->withQueryParams($queryParams)->getUri()->getQuery();

        \Http\Response\send(new Response(302, ['Location' => $url]));
		exit();
	}

	require_once __DIR__ . '/core/php/core.inc.php';

    if (!\in_array($queryParams['v'], ['m', 'd'], true)) {
        throw new \Exception("Erreur : veuillez contacter l'administrateur");
    }

	if ($queryParams['v'] === 'd') {
		if (isset($queryParams['modal'])) {
			try {
				include_file('core', 'authentification', 'php');
				include_file('desktop', init('modal'), 'modal', init('plugin'));
			} catch (Exception $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			}
		} elseif (isset($queryParams['configure'])) {
			include_file('core', 'authentification', 'php');
			include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
		} elseif (isset($queryParams['ajax']) && $queryParams['ajax'] == 1) {
			try {
				$title = 'Jeedom';
				if (init('m') != '') {
					try {
						$plugin = plugin::byId(init('m'));
						if (is_object($plugin)) {
							$title = $plugin->getName() . ' - Jeedom';
						}
					} catch (Exception $e) {

					} catch (Error $e) {

					}
				} else if (init('p') != '') {
					$title = ucfirst(init('p')) . ' - ' . config::byKey('product_name');
				}
				echo '<script>';
				echo 'document.title = "' . $title . '"';
				echo '</script>';
				include_file('core', 'authentification', 'php');
				include_file('desktop', init('p'), 'php', init('m'));
			} catch (Exception $e) {
				ob_end_clean();
				echo '<div class="alert alert-danger div_alert">';
				echo translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
				echo '</div>';
			}
		} else {
			include_file('desktop', 'index', 'php');
		}
	} else {
		$_fn = 'index';
		$_type = 'html';
		$_plugin = '';
		if (isset($queryParams['modal'])) {
			$_fn = init('modal');
			$_type = 'modalhtml';
			$_plugin = init('plugin');
		} elseif (isset($queryParams['p'], $queryParams['ajax'])) {
			$_fn = $queryParams['p'];
			$_plugin = isset($queryParams['m']) ? $queryParams['m'] : $_plugin;
		}
		include_file('mobile', $_fn, $_type, $_plugin);
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

function userAgentIsMobile($userAgent)
{
    return (false !== stripos($userAgent, 'Android')
        || strpos($userAgent, 'iPod')
        || strpos($userAgent, 'iPhone')
        || strpos($userAgent, 'Mobile')
        || strpos($userAgent, 'WebOS')
        || strpos($userAgent, 'mobile')
        || strpos($userAgent, 'hp-tablet')
    );
}

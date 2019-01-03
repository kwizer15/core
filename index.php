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

use Guzzle\Http\Message\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Jeddom\Core\Application;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $application = new Application(__DIR__);
    $request = ServerRequest::fromGlobals();
    \Http\Response\send($application->run($request));
} catch (\Exception $e) {
    \Http\Response\send(new Response(403, [], $e->getMessage()));
}

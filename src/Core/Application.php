<?php

namespace Jeddom\Core;

use Dotenv\Exception\InvalidPathException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    const VIEWER_MOBILE = 'm';
    const VIEWER_DESKTOP = 'd';

    private $basePath;

    private $viewer;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function run(ServerRequestInterface $request)
    {
        try {
            $this->registerEnvironementVariables();
        } catch (InvalidPathException $e) {
            return new Response(302, ['Location' => 'install/setup.php']);
        }

        $queryParams = $request->getQueryParams();

        if (isset($queryParams['v'])) {
            $this->viewer = $queryParams['v'];
        } else {
            $userAgent = $request->hasHeader('HTTP_USER_AGENT')
                ? $request->getHeader('HTTP_USER_AGENT')
                : 'none'
            ;
            $queryParams['v'] = self::isUserAgentMobile($userAgent) ? self::VIEWER_MOBILE : self::VIEWER_DESKTOP;
            $url = $request->withQueryParams($queryParams)->getUri()->getQuery();

            return new Response(302, ['Location' => $url]);
        }

        require_once $this->basePath . '/core/php/core.inc.php';

        if ($this->viewer !== self::VIEWER_MOBILE && $this->viewer !== self::VIEWER_DESKTOP) {
            throw new \Exception("Erreur : veuillez contacter l'administrateur");
        }
        ob_start();

        if ($this->viewer === self::VIEWER_DESKTOP) {
            if (isset($queryParams['modal'])) {
                try {
                    include_file('core', 'authentification', 'php');
                    include_file('desktop', init('modal'), 'modal', init('plugin'));
                } catch (\Exception $e) {
                    ob_end_clean();
                    echo '<div class="alert alert-danger div_alert">';
                    echo \translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
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
                        } catch (\Exception $e) {

                        }
                    } else if (init('p') != '') {
                        $title = ucfirst(init('p')) . ' - ' . \config::byKey('product_name');
                    }
                    echo '<script>';
                    echo 'document.title = "' . $title . '"';
                    echo '</script>';
                    include_file('core', 'authentification', 'php');
                    include_file('desktop', init('p'), 'php', init('m'));
                } catch (\Exception $e) {
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
            }
            elseif (isset($queryParams['p'], $queryParams['ajax'])) {
                $_fn = $queryParams['p'];
                $_plugin = isset($queryParams['m']) ? $queryParams['m'] : $_plugin;
            }
            include_file('mobile', $_fn, $_type, $_plugin);
        }
        $body = ob_get_contents();

        return new Response(200, [], $body);
    }

    private function registerEnvironementVariables()
    {
        (new \Dotenv\Dotenv(__DIR__))->load();
    }

    private static function isUserAgentMobile($userAgent)
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
}

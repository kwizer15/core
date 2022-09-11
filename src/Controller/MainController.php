<?php

declare(strict_types=1);

namespace Jeedom\Controller;

use config;
use Exception;
use Error;
use GuzzleHttp\Psr7\Response;
use plugin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use translate;

class MainController
{
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    final public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->isJeedomInstalled()) {
            return new Response(302, [
                'Location' => 'install/setup.php',
            ]);
        }

        $queryParams = $request->getQueryParams();

        //dunno desktop or mobile:
        if (!isset($queryParams['v'])) {
            $serverParams = $request->getServerParams();
            $useragent = (isset($serverParams["HTTP_USER_AGENT"])) ? $serverParams["HTTP_USER_AGENT"] : 'none';
            $getParams = (stristr($useragent, "Android") || strpos($useragent, "iPod") || strpos($useragent, "iPhone") || strpos($useragent, "Mobile") || strpos($useragent, "WebOS") || strpos($useragent, "mobile") || strpos($useragent, "hp-tablet")) ? 'm' : 'd';
            foreach ($queryParams as $var => $value) {
                if (is_array($value)) {
                    continue;
                }
                $getParams .= '&' . $var . '=' . $value;
            }
            $url = 'index.php?v=' . trim($getParams, '&');

            // why ? which case ?
            if ($this->areHeadersSent()) {
                return new Response(200, [], <<<HTML
<script type="text/javascript">window.location.href='{$url}';</script>
HTML
);
            }

            return new Response(302, [
                'Location' => $url,
            ]);
        }

        try {
            require_once $this->basePath . "/core/php/core.inc.php";
            if (isset($queryParams['v']) && $queryParams['v'] == 'd') {
                if (isset($queryParams['modal'])) {
                    try {
                        include_file('core', 'authentification', 'php');
                        if (!isConnect()) {
                            throw new Exception('{{401 - Accès non autorisé}}');
                        }
                        include_file('desktop', init('modal'), 'modal', init('plugin'));
                    } catch (Exception $e) {
                        ob_end_clean();
                        $_div = '<div class="alert alert-danger div_alert">';
                        $_div .= translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                        $_div .= '</div>';
                        echo $_div;
                    } catch (Error $e) {
                        ob_end_clean();
                        $_div = '<div class="alert alert-danger div_alert">';
                        $_div .= translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                        $_div .= '</div>';
                        echo $_div;
                    }
                } elseif (isset($queryParams['configure'])) {
                    include_file('core', 'authentification', 'php');
                    include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
                } elseif (isset($queryParams['ajax']) && $queryParams['ajax'] == 1) {
                    try {
                        $title = config::byKey('product_name');
                        if (init('m') != '') {
                            try {
                                $plugin = plugin::byId(init('m'));
                                if (is_object($plugin)) {
                                    $title = $plugin->getName() . ' - ' . config::byKey('product_name');
                                }
                            } catch (Exception $e) {
                            } catch (Error $e) {
                            }
                        }
                        include_file('core', 'authentification', 'php');
                        include_file('desktop', init('p'), 'php', init('m'));
                    } catch (Exception $e) {
                        ob_end_clean();
                        $_div = '<div class="alert alert-danger div_alert">';
                        $_div .= translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                        $_div .= '</div>';
                        echo $_div;
                    } catch (Error $e) {
                        ob_end_clean();
                        $_div = '<div class="alert alert-danger div_alert">';
                        $_div .= translate::exec(displayException($e), 'desktop/' . init('p') . '.php');
                        $_div .= '</div>';
                        echo $_div;
                    }
                } else {
                    include_file('desktop', 'index', 'php');
                }
                //page title:
                try {
                    if (init('p') != 'message' && !isset($queryParams['configure']) && !isset($queryParams['modal'])) {
                        $title = pageTitle(init('p')) . ' - ' . config::byKey('product_name');
                        echo '<script>document.title = "' . secureXSS($title) . '"</script>';
                    }
                } catch (Exception $e) {
                }
            } elseif (isset($queryParams['v']) && $queryParams['v'] == 'm') {
                $_fn = 'index';
                $_type = 'html';
                $_plugin = '';
                if (isset($queryParams['modal'])) {
                    $_fn = init('modal');
                    $_type = 'modalhtml';
                    $_plugin = init('plugin');
                } elseif (isset($queryParams['p']) && isset($queryParams['ajax'])) {
                    $_fn = $queryParams['p'];
                    $_plugin = isset($queryParams['m']) ? $queryParams['m'] : $_plugin;
                }
                include_file('mobile', $_fn, $_type, $_plugin);
            } else {
                echo "Unexpected error: Contact administrator";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        die;
    }

    protected function isJeedomInstalled(): bool
    {
        return file_exists($this->basePath . '/core/config/common.config.php');
    }

    protected function areHeadersSent(): bool
    {
        return headers_sent();
    }
}

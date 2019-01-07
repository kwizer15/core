<?php

namespace Jeedom\Core\Application\Configuration;

class ConfigurationTools
{
    /**
     * @param int $_car
     *
     * @return string
     * @throws \Exception
     */
    public static function genKey($_car = 32)
    {
        $key = '';
        $chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for ($i = 0; $i < $_car; $i++) {
            if (function_exists('random_int')) {
                $key .= $chaine[random_int(0, strlen($chaine) - 1)];
            } else {
                $key .= $chaine[rand(0, strlen($chaine) - 1)];
            }
        }
        return $key;
    }

    /*     * *********************Action sur config************************* */

    public static function postConfig_market_allowDNS($_value)
    {
        if ($_value == 1) {
            if (!network::dns_run()) {
                network::dns_start();
            }
        } else {
            if (network::dns_run()) {
                network::dns_stop();
            }
        }
    }

    public static function preConfig_market_password($_value)
    {
        if (!is_sha1($_value)) {
            return sha1($_value);
        }
        return $_value;
    }
}

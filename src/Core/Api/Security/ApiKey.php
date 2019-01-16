<?php

namespace Jeedom\Core\Api\Security;

class ApiKey
{
    /**
     * @param int $nbChars
     *
     * @return string
     * @throws \Exception
     */
    public static function generate($nbChars = 32): string {
        $key = '';
        $chaine = 'abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $nbChars; $i++) {
            if (function_exists('random_int')) {
                $key .= $chaine[random_int(0, strlen($chaine) - 1)];
            } else {
                $key .= $chaine[rand(0, strlen($chaine) - 1)];
            }
        }

        return $key;
    }
}

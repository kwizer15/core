<?php

namespace Jeedom\Core\Application\Configuration;

interface Configuration
{
    /**
     * Ajoute une clef à la config
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function set($key, $value);

    /**
     * Retourne la valeur d'une clef
     *
     * @param $key
     * @param mixed|null $default
     *
     * @return string valeur de la clef
     */
    public function get($key, $default = null);

    /**
     * @param array $keys
     * @param null $default
     *
     * @return mixed
     */
    public function multiGet(array $keys, $default = null);

    /**
     * Supprime une clef de la config
     *
     * @param $key
     *
     * @return bool vrai si ok faux sinon
     */
    public function remove($key);

    public function search($key);
}

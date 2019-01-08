<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;

class InMemoryConfiguration implements Configuration
{
    private $configuration = [];

    private $plugin;
    /**
     * InMemoryConfiguration constructor.
     *
     * @param string $plugin
     *
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Ajoute une clef à la config
     *
     * @param string $key
     * @param mixed $value
     *
     * @return boolean
     */
    public function set($key, $value)
    {
        $this->configuration[$key] = $value;

        return true;
    }

    /**
     * Retourne la valeur d'une clef
     *
     * @param $key
     * @param mixed|null $default
     *
     * @return string valeur de la clef
     */
    public function get($key, $default = null)
    {
        if (isset($this->configuration[$key])) {
            return $this->configuration[$key];
        }

        return $default;
    }

    /**
     * @param array $keys
     * @param null $default
     *
     * @return mixed
     */
    public function multiGet(array $keys, $default = null)
    {
        $keys = array_flip($keys);
        $defined = array_intersect_key($this->configuration, $keys);

        if (!is_array($default)) {
            $rest = array_diff_key($keys, $this->configuration);
            $default = array_fill_keys(array_keys($rest), $default);
        }

        return array_merge($default, $defined);
    }

    /**
     * Supprime une clef de la config
     *
     * @param $key
     *
     * @return bool vrai si ok faux sinon
     */
    public function remove($key)
    {
        unset($this->configuration[$key]);

        return true;
    }

    public function search($key)
    {
        if (null !== $this->get($key)) {
            return [$key => $this->get($key)];
        }

        return [];
    }
}

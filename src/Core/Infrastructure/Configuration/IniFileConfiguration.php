<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;

class IniFileConfiguration implements Configuration
{
    private $configuration;

    public function __construct($plugin)
    {
        $this->configuration = [];

        // TODO: Revoir tout ça
        $baseDir = dirname(__DIR__, 4);
        if ($plugin === 'core') {
            $defaultConfigFilename = $baseDir . '/core/config/default.config.ini';
            $this->configuration = parse_ini_file($defaultConfigFilename, true);

            $customConfigFilename = $baseDir . '/data/custom/custom.config.ini';
            if (file_exists($customConfigFilename)) {
                $this->configuration = array_merge($this->configuration, parse_ini_file($customConfigFilename, true));
            }
        } else {
            $filename = $baseDir . '/plugins/' . $plugin . '/core/config/' . $plugin . '.config.ini';
            if (file_exists($filename)) {
                $this->configuration = parse_ini_file($filename, true);
            }
        }
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
        // Lecture seule
        return false;
    }

    public function get($key, $default = null)
    {
        return isset($this->configuration[$key]) ? $this->configuration[$key] : $default;
    }

    public function multiGet(array $keys, $default = null)
    {
        return array_intersect_key($this->configuration, $keys);
    }

    public function remove($key)
    {
        // Lecture seule
        return false;
    }

    public function search($key)
    {
        // Pas de méthode de recherche ici.
        return isset($this->configuration[$key]) ? [$this->configuration[$key]] : [];
    }
}

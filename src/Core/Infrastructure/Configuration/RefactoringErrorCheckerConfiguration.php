<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;

class RefactoringErrorCheckerConfiguration implements Configuration
{
    private $decoratedConfiguration;

    public function __construct(Configuration $decoratedConfiguration)
    {
        $this->decoratedConfiguration = $decoratedConfiguration;
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
        return $this->decoratedConfiguration->set($key, $value);
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
        if ('core' === $default) {
            trigger_error('Please verify $configuration->get() signature (key, default)', E_ERROR);
        }
        return $this->decoratedConfiguration->get($key, $default);
    }

    /**
     * @param array $keys
     * @param null $default
     *
     * @return mixed
     */
    public function multiGet(array $keys, $default = null)
    {
        if ('core' === $default) {
            trigger_error('Please verify $configuration->get() signature (keys, default)', E_ERROR);
        }
        return $this->decoratedConfiguration->multiGet($keys, $default);
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
        return $this->decoratedConfiguration->remove($key);
    }

    public function search($key)
    {
        return $this->decoratedConfiguration->search($key);
}}

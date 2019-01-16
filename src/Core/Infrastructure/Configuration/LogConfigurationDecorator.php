<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Application\Configuration\ReadOnlyConfigurationException;

class LogConfigurationDecorator implements Configuration
{
    /**
     * @var Configuration
     */
    private $decoratedConfiguration;

    /**
     * LogConfigurationDecorator constructor.
     *
     * @param $decoratedConfiguration
     *
     */
    public function __construct(Configuration $decoratedConfiguration)
    {
        $this->decoratedConfiguration = $decoratedConfiguration;
    }

    /**
     * Retourne la valeur liée à la clé
     * Si la clé n'existe pas retourne la valeur de $default
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $value = $this->decoratedConfiguration->get($key, $default);
        echo "get({$key}, {$default}) => ".var_export($value, true).PHP_EOL;

        return $value;
    }

    /**
     * Définie une valeur liée à une clé
     * Si la clé existe déjà elle sera écrasée
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws ReadOnlyConfigurationException
     *
     * @return Configuration
     */
    public function set(string $key, $value): Configuration
    {
        echo "set({$key}, {$value})".PHP_EOL;
        return $this->decoratedConfiguration->set($key, $value);
    }

    /**
     * Supprime une clé et sa valeur liée
     *
     * @param string $key
     *
     * @return Configuration
     * @throws ReadOnlyConfigurationException
     */
    public function remove(string $key): Configuration
    {
        echo "remove({$key})".PHP_EOL;
        return $this->decoratedConfiguration->remove($key);
    }

    /**
     * Retourne un tableau clé => valeur des clés demandées
     * Si un clé n'existe pas :
     *   - Si default est un tableau, il retournera la valeur correspondant à la clé associée de default, null sinon
     *   - Sinon il retournera default
     *
     * @param string[] $keys
     *
     * @param mixed[]|mixed|null $default
     *
     * @return mixed[]
     */
    public function multiGet(array $keys, $default = null): array
    {
        $value = $this->decoratedConfiguration->multiGet($keys, $default);
        echo 'multiGet([' .implode(', ', $keys)."], {$default}) => ".var_export($value, true).PHP_EOL;

        return $value;
    }

    /**
     * Retourne les clés/valeur correspondant au pattern
     *
     * @param string $pattern
     *
     * @return mixed[]
     */
    public function search(string $pattern): array
    {
        $value = $this->decoratedConfiguration->search($pattern);
        echo "search({$pattern}) => ".var_export($value, true).PHP_EOL;

        return $value;
    }
}

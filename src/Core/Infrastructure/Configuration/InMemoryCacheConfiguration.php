<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;

class InMemoryCacheConfiguration implements Configuration
{
    /**
     * @var Configuration
     */
    private $decoratedConfiguration;

    private $cache;

    public function __construct(Configuration $decoratedConfiguration)
    {
        $this->decoratedConfiguration = $decoratedConfiguration;
    }

    public function set($key, $value)
    {
        $this->decoratedConfiguration->set($key, $value);
        $this->cache[$key] = $value;
    }

    public function get($key, $default = null)
    {
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->decoratedConfiguration->get($key, $default);
        }

        return $this->cache[$key];
    }

    public function multiGet(array $keys, $default = null)
    {
        $uncachedKeys = [];
        foreach ($keys as $key) {
            if (!isset($this->cache[$key])) {
                $uncachedKeys[] = $key;
            }
        }
        if (!empty($uncachedKeys)) {
            $uncachedValues = $this->decoratedConfiguration->multiGet($uncachedKeys, $default);
            $this->cache = array_merge($this->cache, $uncachedValues);
        }

        return array_intersect($this->cache, $keys);
    }

    public function remove($key)
    {
        $this->decoratedConfiguration->remove($key);
        unset($this->cache[$key]);
    }

    public function search($key)
    {
        // Pas de méthode de recherche ici donc on passe la main et on met en cache quand la clé est absente.
        $values = $this->decoratedConfiguration->search($key);
        foreach ($values as $searchKey => $value) {
            if (!isset($this->cache[$searchKey])) {
                $this->cache[$searchKey] = $value;
            }
        }

        return $values;
    }
}

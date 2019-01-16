<?php

namespace Jeedom\Core\Application\Configuration;

class InMemoryCacheConfigurationDecorator implements Configuration
{
    private $decoratedConfiguration;

    private $cache = [];

    public function __construct(Configuration $configuration)
    {
        $this->decoratedConfiguration = $configuration;
    }
    
    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->decoratedConfiguration->get($key);
        }

        return $this->cache[$key] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): Configuration
    {
        $this->decoratedConfiguration->set($key, $value);
        $this->cache[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): Configuration
    {
        $this->decoratedConfiguration->remove($key);
        unset($this->cache[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null): array
    {
        if (!is_array($default)) {
            $default = array_fill_keys($keys, $default);
        }
        $asKeys = array_flip($keys);
        $restKeys = array_diff_key($asKeys, $this->cache);
        if (!empty($restKeys)) {
            $results = $this->decoratedConfiguration->multiGet(array_keys($restKeys));
            foreach ($results as $key => $value) {
                $this->cache[$key] = $value;
            }
        }

        $result = array_intersect_key($this->cache, $asKeys);

        foreach ($result as $key => $value) {
            if (null === $value) {
                $result[$key] = isset($default[$key]) ? $default[$key] : null;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $pattern): array
    {
        $results = $this->decoratedConfiguration->search($pattern);
        foreach ($results as $key => $value) {
            $this->cache[$key] = $value;
        }

        return $results;
    }
}

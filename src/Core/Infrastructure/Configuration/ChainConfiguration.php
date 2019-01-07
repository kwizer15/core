<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;

class ChainConfiguration implements Configuration
{
    private $configurations = [];

    public function __construct(array $configurations)
    {
        if (empty($configurations)) {
            throw new \LogicException(self::class.' must be constructed with ConfigurationStorage');
        }

        foreach ($configurations as $configuration) {
            if (!$configuration instanceof Configuration) {
                throw new \LogicException(
                    'Class '. \get_class($configuration) . ' must implements '.Configuration::class
                );
            }

            $this->configurations[] = $configuration;
        }
    }

    public function set($key, $value)
    {
        $return = false;
        foreach ($this->configurations as $configuration) {
            $return = $return || $configuration->set($key, $value);
        }

        return $return;
    }

    public function get($key, $default = null)
    {
        foreach ($this->configurations as $configuration) {
            $value = $configuration->get($key);
            if (null !== $value) {
                return $value;
            }
        }

        return $default;
    }

    public function multiGet(array $keys, $default = null)
    {
        $countKeys = \count($keys);
        $countValues = 0;
        $values = [];
        foreach ($this->configurations as $configuration) {
            $tempValues = $configuration->multiGet($keys);
            $values[] = $tempValues;
            $countValues += \count($tempValues);
            if ($countValues === $countKeys) {
                return array_merge(...$values);
            }
            $keys = array_diff_key($tempValues, $keys);
        }

        $tempValues = [];

        if (!\is_array($default)) {
            $default = array_fill_keys($keys, $default);
        }

        foreach ($keys as $key) {
            $tempValues[$key] = $default;
        }

        return array_merge(...$values);
    }

    public function remove($key)
    {
        $return = false;
        foreach ($this->configurations as $configuration) {
            $return = $return || $configuration->remove($key);
        }

        return $return;
    }

    public function search($key)
    {
        $finalValues = [];
        foreach ($this->configurations as $configuration) {
            $values = $configuration->search($key);
            foreach ($values as $searchKey => $value) {
                if (!isset($finalValues[$searchKey])) {
                    $finalValues[$searchKey] = $value;
                }
            }
        }

        return $finalValues;
    }
}

<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Infrastructure\Configuration\Event\Configured;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcherConfigurationDecorator implements Configuration
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Configuration
     */
    private $decoratedConfiguration;

    private $plugin;

    public function __construct($plugin, EventDispatcherInterface $eventDispatcher, Configuration $configuration)
    {
        $this->plugin = $plugin;
        $this->eventDispatcher = $eventDispatcher;
        $this->decoratedConfiguration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        return $this->decoratedConfiguration->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): Configuration
    {
        $event = $this->eventDispatcher->dispatch('preConfig', new Configured($key, $value, $this->plugin));
        $value = $event->getValue();
        $this->decoratedConfiguration->set($key, $value);
        $this->eventDispatcher->dispatch('postConfig', new Configured($key, $value, $this->plugin));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): Configuration
    {
        $this->decoratedConfiguration->remove($key);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null): array
    {
        return $this->decoratedConfiguration->multiGet($keys, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $pattern): array
    {
        return $this->decoratedConfiguration->search($pattern);
    }
}

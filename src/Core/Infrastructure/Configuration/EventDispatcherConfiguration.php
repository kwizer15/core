<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Infrastructure\Event\Configured;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcherConfiguration implements Configuration
{
    private $plugin;

    private $decoratedConfiguration;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        $plugin,
        EventDispatcherInterface $eventDispatcher,
        Configuration $decoratedConfiguration
    ) {
        $this->plugin = $plugin;
        $this->eventDispatcher = $eventDispatcher;
        $this->decoratedConfiguration = $decoratedConfiguration;
    }

    public function set($key, $value)
    {
        $val = is_object($value) || is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE): $value;

        $event = $this->eventDispatcher->dispatch('pre_configured', new Configured($key, $val, $this->plugin));

        $val = $event->getValue();
        $result = $this->decoratedConfiguration->set($key, $val);

        $this->eventDispatcher->dispatch('post_configured', new Configured($key, $val, $this->plugin));

        return $result;
    }

    public function get($key, $default = null)
    {
        return $this->decoratedConfiguration->get($key, $default);
    }

    public function multiGet(array $keys, $default = null)
    {
        return $this->decoratedConfiguration->multiGet($keys, $default);
    }

    public function remove($key)
    {
        return $this->decoratedConfiguration->remove($key);
}

    public function search($key)
    {
        return $this->decoratedConfiguration->search($key);
    }
}

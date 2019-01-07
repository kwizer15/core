<?php

namespace Jeedom\Core\Infrastructure\Event;

use Symfony\Component\EventDispatcher\Event;

class Configured extends Event
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $plugin;

    /**
     * Configured constructor.
     *
     * @param string $key
     * @param mixed $value
     * @param string $plugin
     */
    public function __construct($key, $value, $plugin)
    {
        $this->key = $key;
        $this->value = $value;
        $this->plugin = $plugin;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    public function withValue($value)
    {
        // Immutable version (don't work with standard EventDispatcher
        // return new self($this->>key, $value, $this->>plugin);

        $this->value = $value;

        return $this;
    }
}

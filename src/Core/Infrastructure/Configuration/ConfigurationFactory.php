<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConfigurationFactory
{
    /**
     * @param string $plugin
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return Configuration
     */
    public static function build($plugin, EventDispatcherInterface $eventDispatcher)
    {
        return new EventDispatcherConfiguration(
            $plugin,
            $eventDispatcher,
            new InMemoryCacheConfiguration(
                new ChainConfiguration([
                    new DBConfiguration($plugin),
                    new IniFileConfiguration($plugin),
                ])
            )
        );
    }
}

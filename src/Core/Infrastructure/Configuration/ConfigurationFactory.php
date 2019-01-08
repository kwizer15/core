<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConfigurationFactory
{
    private static $configurations = [];

    /**
     * @var EventDispatcherInterface
     */
    private static $eventDispatcher;

    /**
     * @param string $plugin
     *
     * @return Configuration
     */
    public static function build($plugin = 'core')
    {
        if (null === self::$eventDispatcher) {
            self::$eventDispatcher = new EventDispatcher();
            self::$eventDispatcher->addListener('preConfig', [\config::class, 'listenConfigure']);
            self::$eventDispatcher->addListener('postConfig', [\config::class, 'listenConfigure']);
        }

        if (isset(self::$configurations[$plugin])) {
            return self::$configurations[$plugin];
        }

        $persistence = 'test' === getenv('ENV') ? new InMemoryConfiguration($plugin) : new DBConfiguration($plugin);

        self::$configurations[$plugin] = new RefactoringErrorCheckerConfiguration(
            new EventDispatcherConfiguration(
                $plugin,
                self::$eventDispatcher,
                new InMemoryCacheConfiguration(
                    new ChainConfiguration([
                        $persistence,
                        new IniFileConfiguration($plugin),
                    ])
                )
            ));

        return self::$configurations[$plugin];
    }

    public static function addEventListener($eventName, $callable, $priority = 0)
    {
        self::$eventDispatcher->addListener($eventName, $callable, $priority);
    }
}

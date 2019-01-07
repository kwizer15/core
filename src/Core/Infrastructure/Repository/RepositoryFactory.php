<?php

namespace Jeedom\Core\Infrastructure\Repository;

class RepositoryFactory
{
    public static function build($repositoryClass)
    {
        $prefix = 'test' === getenv('ENV') ? 'InMemory' : 'DB';
        $repositoryClass = str_replace('\\Domain\\', '\\Infrastructure\\', $repositoryClass);
        $explodedClass = explode('\\', $repositoryClass);
        $className = array_pop($explodedClass);
        $explodedClass[] = $prefix.$className;
        $class = implode('\\', $explodedClass);

        return new $class();
    }
}

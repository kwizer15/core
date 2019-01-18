<?php

namespace Jeedom\Core\Infrastructure\Repository\Mapper;

use Jeedom\Core\Infrastructure\Repository\PDORepository;

abstract class AbstractSQLDatabaseMapper
{
    private $reflectionClass;

    private $reflectionProperties;

    public function __construct(PDORepository $repository)
    {
        $className = $repository->getEntityClassName();
        $this->reflectionClass = new \ReflectionClass($className);
        $this->reflectionProperties = $this->reflectionClass->getProperties();
    }

    /**
     * Tableau de la forme
     *      propriété => function (array $row) { return "valeur à mettre dans la propriété"; }
     *
     * fonction de mapping qui renvoi la valeur à mettre dans la propriété
     * Cette fonction prend en paramètre le tableau complet de résultats de la requète SQL
     * On peut donc former une donnée à partir de plusieurs champs.
     *
     * @param array $result
     *
     * @return object
     * @throws \ReflectionException
     */
    public function fromArrayToObject(array $result)
    {
        $mapper = $this->getArrayToObjectMap();
        $className = isset($mapper['@class']) ? $mapper['@class']($result) : null;
        $reflectionClass = $className ? new \ReflectionClass($className) : $this->reflectionClass;
        $reflectionProperties = $reflectionClass->getProperties();
        $object = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyMapper = $mapper[$reflectionProperty->getName()];
            $value = $propertyMapper($result);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($object, $value);
            $reflectionProperty->setAccessible(false);
        }

        return $object;
    }

    /**
     * Même chose dans l'autre sens (l'object est représenté sous forme d'un tableau propriété => valeur)
     *
     * @param object $object
     *
     * @return array
     */
    public function fromObjectToArray($object): array
    {
        $mapper = $this->getObjectToArrayMap();
        $arrayObject = [];
        foreach ($this->reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $arrayObject[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
            $reflectionProperty->setAccessible(false);
        }
        $parameters = [];
        foreach ($mapper as $field => $fieldMapper) {
            $parameters[$field] = $fieldMapper($arrayObject);
        }

        return $parameters;
    }

    abstract protected function getObjectToArrayMap(): array;

    abstract protected function getArrayToObjectMap(): array;
}

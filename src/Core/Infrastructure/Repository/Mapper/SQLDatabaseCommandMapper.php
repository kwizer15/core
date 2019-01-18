<?php

namespace Jeedom\Core\Infrastructure\Repository\Mapper;

class SQLDatabaseCommandMapper extends AbstractSQLDatabaseMapper
{
    /**
     * Tableau de la forme
     *      propriété => function (array $row) { return "valeur à mettre dans la propriété"; }
     *
     * fonction de mapping qui renvoi la valeur à mettre dans la propriété
     * Cette fonction prend en paramètre le tableau complet de résultats de la requète SQL
     * On peut donc former une donnée à partir de plusieurs champs.
     *
     * @return callable[]
     */
    protected function getArrayToObjectMap(): array
    {
        return [
            '@class'        => function (array $row) {
                $className = '\\' . $row['eqType'] . 'Cmd';
                return class_exists($className) ? $className : null;
            },
            'id'            => function (array $row) { return (string) $row['id']; },
            'eqLogic_id'    => function (array $row) { return $row['eqLogic_id']; },
            'eqType'        => function (array $row) { return $row['eqType']; },
            'logicalId'     => function (array $row) { return $row['logicalId']; },
            'generic_type'  => function (array $row) { return $row['generic_type']; },
            'order'         => function (array $row) { return $row['order']; },
            'name'          => function (array $row) { return $row['name']; },
            'configuration' => function (array $row) { return $row['configuration']; },
            'template'      => function (array $row) { return $row['template']; },
            'isHistorized'  => function (array $row) { return $row['isHistorized']; },
            'type'          => function (array $row) { return $row['type']; },
            'subType'       => function (array $row) { return $row['subType']; },
            'unite'         => function (array $row) { return $row['unite']; },
            'display'       => function (array $row) { return $row['display']; },
            'isVisible'     => function (array $row) { return $row['isVisible']; },
            'value'         => function (array $row) { return $row['value']; },
            'html'          => function (array $row) { return $row['html']; },
            'alert'         => function (array $row) { return $row['alert']; },
        ];
    }

    /**
     * Même chose dans l'autre sens (la commande est représentée sous forme d'un tableau propriété => valeur)
     *
     * @return callable[]
     */
    protected function getObjectToArrayMap(): array
    {
        return [
            'id'            => function (array $cmd) { return (int) $cmd['id']; },
            'eqLogic_id'    => function (array $cmd) { return $cmd['eqLogic_id']; },
            'eqType'        => function (array $cmd) { return $cmd['eqType']; },
            'logicalId'     => function (array $cmd) { return $cmd['logicalId']; },
            'generic_type'  => function (array $cmd) { return $cmd['generic_type']; },
            'order'         => function (array $cmd) { return $cmd['order']; },
            'name'          => function (array $cmd) { return $cmd['name']; },
            'configuration' => function (array $cmd) { return $cmd['configuration']; },
            'template'      => function (array $cmd) { return $cmd['template']; },
            'isHistorized'  => function (array $cmd) { return $cmd['isHistorized']; },
            'type'          => function (array $cmd) { return $cmd['type']; },
            'subType'       => function (array $cmd) { return $cmd['subType']; },
            'unite'         => function (array $cmd) { return $cmd['unite']; },
            'display'       => function (array $cmd) { return $cmd['display']; },
            'isVisible'     => function (array $cmd) { return $cmd['isVisible']; },
            'value'         => function (array $cmd) { return $cmd['value']; },
            'html'          => function (array $cmd) { return $cmd['html']; },
            'alert'         => function (array $cmd) { return $cmd['alert']; },
        ];
    }
}

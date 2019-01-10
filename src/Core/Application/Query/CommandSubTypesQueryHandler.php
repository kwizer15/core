<?php

namespace Jeedom\Core\Application\Query;

use Jeedom\Common\Application\Query\QueryHandler;

class CommandSubTypesQueryHandler implements QueryHandler
{
    /**
     * @param CommandSubTypesQuery $query
     *
     * @return array
     * @throws \Exception
     */
    public function handle($query)
    {
        $type = $query->getType();
        $values = [];
        $sql = 'SELECT distinct(subType) as subtype';
        if ($type != '') {
            $values['type'] = $type;
            $sql .= ' WHERE type=:type';
        }
        $sql .= ' FROM cmd';
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);
    }
}

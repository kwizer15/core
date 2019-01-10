<?php

namespace Jeedom\Core\Application\Query;

class CommandTypesQueryHandler
{
    /**
     * @param CommandTypesQuery $query
     *
     * @return array
     * @throws \Exception
     */
    public function handle(CommandTypesQuery $query)
    {
        $sql = 'SELECT DISTINCT(type) as type FROM cmd';

        return \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);
    }
}

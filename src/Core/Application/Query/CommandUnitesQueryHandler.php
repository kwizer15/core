<?php

namespace Jeedom\Core\Application\Query;

class CommandUnitesQueryHandler
{
    /**
     * @param CommandUnitesQuery $query
     *
     * @return array
     * @throws \Exception
     */
    public function handle(CommandUnitesQuery $query)
    {
        $sql = 'SELECT distinct(unite) as unite FROM cmd';

        return \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);
    }
}

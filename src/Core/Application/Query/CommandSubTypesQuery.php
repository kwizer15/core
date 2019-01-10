<?php

namespace Jeedom\Core\Application\Query;

use Jeedom\Common\Application\Query\Query;

class CommandSubTypesQuery implements Query
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}

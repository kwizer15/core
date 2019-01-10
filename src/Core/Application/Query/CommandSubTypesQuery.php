<?php

namespace Jeedom\Core\Application\Query;

class CommandSubTypesQuery
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

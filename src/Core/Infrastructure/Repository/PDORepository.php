<?php

namespace Jeedom\Core\Infrastructure\Repository;

interface PDORepository
{
    /**
     * @return string
     */
    public function getEntityClassName(): string;
}

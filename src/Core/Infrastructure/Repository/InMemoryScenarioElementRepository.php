<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioElementRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

// TODO: à impléméenter
class InMemoryScenarioElementRepository implements ScenarioElementRepository
{
    /**
     * @param int $id
     *
     * @return \scenarioElement
     * @throws \Exception
     */
    public function get($id)
    {
        $values = array(
            'id' => $id,
        );
        $sql = 'SELECT ' . Connection::buildField(\scenarioElement::class)
            . ' FROM ' . \scenarioElement::class
            . ' WHERE id=:id'
        ;

        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenarioElement::class);
    }

    /**
     * @param \scenarioElement $scenarioElement
     *
     * @return void
     */
    public function save(\scenarioElement $scenarioElement)
    {
        // TODO: Implement save() method.
    }
}

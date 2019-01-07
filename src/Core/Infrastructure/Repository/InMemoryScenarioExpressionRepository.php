<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScenarioExpressionRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

// TODO: à impléméenter
class InMemoryScenarioExpressionRepository implements ScenarioExpressionRepository
{
    public function get($id)
    {
        $values = [
            'id' => $id,
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenarioExpression::class) . '
        FROM ' . \scenarioExpression::class . '
        WHERE id=:id';
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenarioExpression::class);
    }

    public function all()
    {
        $sql = 'SELECT ' . Connection::buildField(\scenarioExpression::class) . '
        FROM ' . \scenarioExpression::class;
        return Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenarioExpression::class);
    }

    public function findByScenarioSubElementId($scenarioSubElementId)
    {
        $values = [
            'scenarioSubElement_id' => $scenarioSubElementId,
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenarioExpression::class) . '
        FROM ' . \scenarioExpression::class . '
        WHERE scenarioSubElement_id=:scenarioSubElement_id
        ORDER BY `order`';
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenarioExpression::class);
    }

    public function searchExpression($expression, $options = null, $and = true)
    {
        $values = [
            'expression' => '%' . $expression . '%',
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenarioExpression::class) . '
        FROM ' . \scenarioExpression::class . '
        WHERE expression LIKE :expression';
        if ($options !== null) {
            $values['options'] = '%' . $options . '%';
            if ($and) {
                $sql .= ' AND options LIKE :options';
            } else {
                $sql .= ' OR options LIKE :options';
            }
        }
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \scenarioExpression::class);
    }

    public function findByElement($elementId)
    {
        $values = [
            'expression' => $elementId,
        ];
        $sql = 'SELECT ' . Connection::buildField(\scenarioExpression::class) . '
        FROM ' . \scenarioExpression::class . '
        WHERE expression=:expression
        AND `type`= "element"';
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \scenarioExpression::class);
    }
}

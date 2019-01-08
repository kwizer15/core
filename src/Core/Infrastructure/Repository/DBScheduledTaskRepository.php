<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScheduledTaskRepository;
use Jeedom\Core\Infrastructure\Database\Connection;

class DBScheduledTaskRepository implements ScheduledTaskRepository
{
    /**
     * Return an array of all cron object
     *
     * @param bool $_order
     *
     * @return array
     * @throws \ReflectionException
     */
    public function all($_order = false)
    {
        $sql = 'SELECT ' . Connection::buildField(\cron::class) . ' FROM cron';
        if ($_order) {
            $sql .= ' ORDER BY deamon DESC';
        }
        return Connection::Prepare($sql, array(), Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cron::class);
    }

    /**
     * Get cron object associate to id
     *
     * @param int $_id
     *
     * @return object
     * @throws \ReflectionException
     */
    public function get($_id)
    {
        $value = array(
            'id' => $_id,
        );
        $sql = 'SELECT ' . Connection::buildField(\cron::class) . '
        FROM cron
        WHERE id=:id';
        return Connection::Prepare($sql, $value, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cron::class);
    }

    /**
     * Return cron object corresponding to parameters
     *
     * @param string $_class
     * @param string $_function
     * @param string $_option
     *
     * @return \cron[]
     * @throws \ReflectionException
     */
    public function findByClassAndFunction($_class, $_function, $_option = '')
    {
        $value = array(
            'class' => $_class,
            'function' => $_function,
        );
        $sql = 'SELECT ' . Connection::buildField(\cron::class) . '
        FROM cron
        WHERE class=:class
        AND function=:function';
        if ($_option != '') {
            $_option = json_encode($_option, JSON_UNESCAPED_UNICODE);
            $value['option'] = $_option;
            $sql .= ' AND `option`=:option';
        }
        return Connection::Prepare($sql, $value, Connection::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \cron::class);
    }

    /**
     *
     * @param type $_class
     * @param type $_function
     * @param string $_option
     *
     * @return \cron[]
     * @throws \ReflectionException
     */
    public function searchClassAndFunction($_class, $_function, $_option = '')
    {
        $value = array(
            'class' => $_class,
            'function' => $_function,
        );
        $sql = 'SELECT ' . Connection::buildField(\cron::class) . '
        FROM cron
        WHERE class=:class
        AND function=:function';
        if ($_option != '') {
            $value['option'] = '%' . $_option . '%';
            $sql .= ' AND `option` LIKE :option';
        }

        return Connection::Prepare($sql, $value, Connection::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \cron::class);
    }

    /**
     * Save cron object
     *
     * @param \cron $cron
     *
     * @return ScheduledTaskRepository
     * @throws \Exception
     */
    public function add(\cron $cron)
    {
        Connection::save($cron, false, true);

        return $this;
    }

    /**
     * Remove cron object
     *
     * @param $id
     * @param bool $halt_before
     *
     * @return ScheduledTaskRepository
     * @throws \ReflectionException
     */
    public function remove($id, $halt_before = true)
    {
        $cron = $this->get($id);
        if ($halt_before && $cron->running()) {
            $cron->halt();
        }
        \cache::delete('cronCacheAttr' . $cron->getId());
        Connection::remove($cron);

        return $this;
    }

    /**
     * Déstinée à être supprimée
     *
     * @param $cron
     *
     * @return mixed
     */
    public function refresh($cron)
    {
        // TODO: Implement refresh() method.
}}

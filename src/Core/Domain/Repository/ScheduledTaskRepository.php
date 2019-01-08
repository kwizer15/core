<?php

namespace Jeedom\Core\Domain\Repository;

interface ScheduledTaskRepository
{
    /**
     * Return an array of all cron object
     *
     * @param bool $_order
     *
     * @return \cron[]
     */
    public function all($_order = false);

    /**
     * Get cron object associate to id
     *
     * @param int $_id
     *
     * @return \cron
     */
    public function get($_id);

    /**
     * Return cron object corresponding to parameters
     *
     * @param string $_class
     * @param string $_function
     * @param string $_option
     *
     * @return \cron
     */
    public function findByClassAndFunction($_class, $_function, $_option = '');

    /**
     *
     * @param string $_class
     * @param string $_function
     * @param string $_option
     *
     * @return \cron[]
     * @throws \ReflectionException
     */
    public function searchClassAndFunction($_class, $_function, $_option = '');

    /**
     * Save cron object
     *
     * @param \cron $cron
     *
     * @return ScheduledTaskRepository
     */
    public function add(\cron $cron);

    /**
     * Remove cron object
     *
     * @param $id
     * @param bool $halt_before
     *
     * @return ScheduledTaskRepository
     */
    public function remove($id, $halt_before = true);
}

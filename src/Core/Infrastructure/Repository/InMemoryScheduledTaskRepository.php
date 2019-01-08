<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\ScheduledTaskRepository;

class InMemoryScheduledTaskRepository implements ScheduledTaskRepository
{
    /**
     * @var \cron[]
     */
    private $fixtures = [];

    /**
     * Return an array of all cron object
     *
     * @param bool $_order
     *
     * @return array
     */
    public function all($_order = false)
    {
        return $this->fixtures;
    }

    /**
     * Get cron object associate to id
     *
     * @param $id
     *
     * @return \cron
     */
    public function get($id)
    {
        if (isset($this->fixtures[$id])) {
            return $this->fixtures[$id];
        }

        return null;
    }

    /**
     * Return cron object corresponding to parameters
     *
     * @param string $_class
     * @param string $_function
     * @param string $_option
     *
     * @return \cron[]
     */
    public function findByClassAndFunction($_class, $_function, $_option = null)
    {
        $results = [];
        foreach ($this->fixtures as $fixture) {
            if ($fixture->getClass() === $_class
                && $fixture->getFunction() === $_function
                && ($_option === null || $fixture->getOption() == $_option)
            ) {
                $results[] = $fixture;
            }
        }

        return $results;
    }

    /**
     *
     * @param type $_class
     * @param type $_function
     * @param string $_option
     *
     * @return \cron[]
     */
    public function searchClassAndFunction($_class, $_function, $_option = null)
    {
        $results = [];
        foreach ($this->fixtures as $fixture) {
            if (false !== stripos($fixture->getClass(), $_class)
                && false !== stripos($fixture->getFunction(), $_function)
                && ($_option === null || $fixture->getOption() == $_option)
            ) {
                $results[] = $fixture;
            }
        }

        return $results;
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
        $this->fixtures[] = $cron;

        return $this;
    }

    /**
     * Remove cron object
     *
     * @param $id
     * @param bool $halt_before
     *
     * @return ScheduledTaskRepository
     */
    public function remove($id, $halt_before = true)
    {
        unset($this->fixtures[$id]);

        return $this;
    }
}

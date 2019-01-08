<?php

namespace Tests;

use Jeedom\Core\Domain\Repository\ScheduledTaskRepository;
use Jeedom\Core\Infrastructure\Repository\RepositoryFactory;

class cronTest extends \PHPUnit_Framework_TestCase
{
	public function testCreate()
    {
        /** @var ScheduledTaskRepository $scheduledTaskRepository */
        $scheduledTaskRepository = RepositoryFactory::build(ScheduledTaskRepository::class);
		$cron1 = new \cron();
		$cron1->setClass('calendar');
		$cron1->setFunction('pull');
		$cron1->setLastRun(date('Y-m-d H:i:s'));
		$cron1->setSchedule('00 00 * * * 2020');
        $scheduledTaskRepository->add($cron1);

		$cron2 = new \cron();
		$cron2->setClass('calendar');
		$cron2->setFunction('pull');
		$cron2->setLastRun(date('Y-m-d H:i:s'));
		$cron2->setSchedule('00 00 * * * 2020');
        $scheduledTaskRepository->add($cron2);

		$this->assertSame($cron1->getId(), $cron2->getId());

		$cron1 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull');
		if (!is_object($cron1)) {
            // FIXME: pas d'envoi d'exception dans les tests
			throw new Exception("Impossible de trouver calend::pull");
		}
        $scheduledTaskRepository->remove($cron1);
	}

	public function testCreateWithOption()
    {
        /** @var ScheduledTaskRepository $scheduledTaskRepository */
        $scheduledTaskRepository = RepositoryFactory::build(ScheduledTaskRepository::class);
		$cron1 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull', array('event_id' => 1));
		if (!is_object($cron1)) {
			$cron1 = new \cron();
			$cron1->setClass('calendar');
			$cron1->setFunction('pull');
			$cron1->setOption(array('event_id' => 1));
			$cron1->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron1->setSchedule('00 00 * * * 2020');
        $scheduledTaskRepository->add($cron1);

		$cron2 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull', array('event_id' => 2));
		if (!is_object($cron2)) {
			$cron2 = new \cron();
			$cron2->setClass('calendar');
			$cron2->setFunction('pull');
			$cron2->setOption(array('event_id' => 2));
			$cron2->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron2->setSchedule('00 00 * * * 2020');
        $scheduledTaskRepository->add($cron2);

		$this->assertNotSame($cron1->getId(), $cron2->getId());

		$cron3 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull', array('event_id' => 1));
		if (!is_object($cron3)) {
			$cron3 = new \cron();
			$cron3->setClass('calendar');
			$cron3->setFunction('pull');
			$cron3->setOption(array('event_id' => 1));
			$cron3->setLastRun(date('Y-m-d H:i:s'));
		}
		$cron3->setSchedule('00 00 * * * 2020');
        $scheduledTaskRepository->add($cron3);

		$this->assertSame($cron1->getId(), $cron3->getId());

		$cron1 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull', array('event_id' => 1));
		if (!is_object($cron1)) {
		    // FIXME: pas d'envoi d'exception dans les tests
			throw new \Exception("Impossible de trouver calend::pull (1)");
		}
        $scheduledTaskRepository->remove($cron1);
		$cron2 = $scheduledTaskRepository->findByClassAndFunction('calendar', 'pull', array('event_id' => 2));
		if (!is_object($cron2)) {
            // FIXME: pas d'envoi d'exception dans les tests
			throw new \Exception("Impossible de trouver calend::pull (2)");
		}
        $scheduledTaskRepository->remove($cron2);
	}
}


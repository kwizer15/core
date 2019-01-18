<?php

namespace Jeedom\Core\Presenter\Service;

use Jeedom\Core\Infrastructure\Service\type;

interface HumanScenarioMap
{
    /**
     * @param object $_input
     *
     * @return string
     * @throws \ReflectionException
     */
    public function toHumanReadable($_input);

    /**
     *
     * @param type $_input
     *
     * @return type
     * @throws \ReflectionException
     */
    public function fromHumanReadable($_input);
}

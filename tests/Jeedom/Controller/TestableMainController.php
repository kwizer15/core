<?php

declare(strict_types=1);

namespace Tests\Jeedom\Controller;

use Jeedom\Controller\MainController;

final class TestableMainController extends MainController
{
    /**
     * @var bool
     */
    public $jeedomInstalled;

    /**
     * @var bool
     */
    public $headersSent;

    protected function isJeedomInstalled(): bool
    {
        return $this->jeedomInstalled ?? parent::isJeedomInstalled();
    }

    protected function areHeadersSent(): bool
    {
        return $this->headersSent ?? parent::areHeadersSent();
    }
}

<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Command;

use Helthe\Component\Chronos\CronJobScheduler;
use Helthe\Component\Chronos\Crontab;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Base class for HeltheChronosBundle commands.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * Get Crontab service.
     *
     * @return Crontab
     */
    protected function getCrontab()
    {
        return $this->getContainer()->get('helthe_chronos.crontab');
    }

    /**
     * Get the default user.
     *
     * @return string
     */
    protected function getDefaultUser()
    {
        return $this->getContainer()->getParameter('helthe_chronos.crontab.default_user');
    }

    /**
     * Get CronJobScheduler service.
     *
     * @return CronJobScheduler
     */
    protected function getJobScheduler()
    {
        return $this->getContainer()->get('helthe_chronos.job_scheduler');
    }
}

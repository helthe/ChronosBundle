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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Runs all scheduled cron jobs.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class RunCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('helthe:chronos:run')
            ->setDescription('Runs all scheduled cron jobs.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getJobScheduler()->runJobs();

        $output->writeln('All scheduled cron jobs have been run successfully!');

        return 0;
    }
}

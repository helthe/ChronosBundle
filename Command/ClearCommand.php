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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clears the crontab.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ClearCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('helthe:chronos:clear')
            ->setDescription('Clears the crontab.')
            ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'The user crontab to clear.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getDefaultUser();

        if ($input->hasOption('user')) {
            $user = $input->getOption('user');
        }

        $this->getCrontab()->clear($user);

        $output->writeln('Crontab cleared successfully!');

        return 0;
    }
}

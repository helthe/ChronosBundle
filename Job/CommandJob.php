<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Job;

use Helthe\Component\Chronos\Job\CommandJob as BaseCommandJob;

/**
 * Job to run a symfony command.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class CommandJob extends BaseCommandJob
{
    /**
     * Constructor.
     *
     * @param mixed  $expression
     * @param string $command
     * @param string $kernelRootDir
     */
    public function __construct($expression, $command, $kernelRootDir)
    {
        $command = $kernelRootDir . '/console ' . $command;

        parent::__construct($expression, $command);
    }
}

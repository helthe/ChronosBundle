<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\Job;

use Helthe\Bundle\ChronosBundle\Job\CommandJob;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ComamndJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers CommandJob::getCommand
     */
    public function testGetCommand()
    {
        $job = new CommandJob('@hourly', 'helthe:chronos:update', '/path/to/sf/kernel');

        $this->assertEquals('/path/to/sf/kernel/console helthe:chronos:update', $job->getCommand());
    }
}

<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\Annotation;

use Helthe\Bundle\ChronosBundle\Annotation\CronJob;

class CronJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Helthe\Bundle\ChronosBundle\Exception\MissingOptionException
     */
    public function testEmptyContructor()
    {
        $annotation = new CronJob(array());
    }

    /**
     * @expectedException \Helthe\Bundle\ChronosBundle\Exception\InvalidOptionException
     */
    public function testInvalidOption()
    {
        $annotation = new CronJob(array('value' => '@hourly', 'foo' => 'bar'));
    }

    /**
     * @expectedException \Helthe\Bundle\ChronosBundle\Exception\InvalidOptionException
     */
    public function testInvalidType()
    {
        $annotation = new CronJob(array('value' => '@hourly', 'type' => 'other'));
    }

    public function testBasicContructor()
    {
        $annotation = new CronJob(array('value' => '@hourly'));

        $this->assertEquals('@hourly', $annotation->expression);
        $this->assertEquals('scheduler', $annotation->type);
    }

    public function testAdvancedContructor()
    {
        $annotation = new CronJob(array('expression' => '@hourly', 'type' => 'crontab'));

        $this->assertEquals('@hourly', $annotation->expression);
        $this->assertEquals('crontab', $annotation->type);
    }
}

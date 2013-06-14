<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Helthe\Bundle\ChronosBundle\Metadata\Driver\AnnotationDriver;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    public function testSchedulerAnnotation()
    {
        $metadata = $this->getDriver()->loadMetadataForClass(new \ReflectionClass('Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job\SchedulerJob'));

        $this->assertEquals('helthe.bundle.chronos_bundle.tests.fixture.bundle.test_bundle.job.scheduler_job', $metadata->id);
        $this->assertEquals('@yearly', $metadata->expression);
        $this->assertEquals('scheduler', $metadata->type);
    }

    public function testCrontabAnnotation()
    {
        $metadata = $this->getDriver()->loadMetadataForClass(new \ReflectionClass('Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job\CrontabJob'));

        $this->assertEquals('helthe.bundle.chronos_bundle.tests.fixture.bundle.test_bundle.job.crontab_job', $metadata->id);
        $this->assertEquals('@hourly', $metadata->expression);
        $this->assertEquals('crontab', $metadata->type);
    }

    /**
     * Get annotation driver.
     *
     * @return AnnotationDriver
     */
    private function getDriver()
    {
        return new AnnotationDriver(new AnnotationReader());
    }
}

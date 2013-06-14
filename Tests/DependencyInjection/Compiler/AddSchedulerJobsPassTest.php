<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\DependencyInjection\Compiler;

use Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler\AddSchedulerJobsPass;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AddSchedulerJobsPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithoutJobSchedulerDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('helthe_chronos.job_scheduler')
            ->will($this->returnValue(false));

        $scheduler = new AddSchedulerJobsPass();
        $scheduler->process($container);
    }

    public function testProcessWithJobSchedulerDefintion()
    {
        $definitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();
        $definitionMock->expects($this->once())
            ->method('replaceArgument')
            ->with($this->equalTo(0), $this->isType('array'));

        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->with('helthe_chronos.job_scheduler')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('helthe_chronos.job.scheduler'))
            ->will($this->returnValue(array('id' => array('helthe_chronos.job.scheduler'))));
        $containerBuilderMock->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('helthe_chronos.job_scheduler'))
            ->will($this->returnValue($definitionMock));

        $crontabJobsPass = new AddSchedulerJobsPass();
        $crontabJobsPass->process($containerBuilderMock);
    }
}

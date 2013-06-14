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

use Doctrine\Common\Annotations\AnnotationReader;
use Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler\AnnotationConfigurationPass;
use Helthe\Bundle\ChronosBundle\Metadata\Driver\AnnotationDriver;
use Metadata\ClassHierarchyMetadata;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AnnotationConfigurationPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithDisabledAnnotations()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('getParameter')
            ->with('helthe_chronos.enable_annotations')
            ->will($this->returnValue(false));

        $annotationPass = new AnnotationConfigurationPass();
        $annotationPass->process($containerBuilderMock);
    }

    public function testProcessWithNoBundles()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->at(0))
            ->method('getParameter')
            ->with('helthe_chronos.enable_annotations')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->at(1))
            ->method('getParameter')
            ->with('kernel.bundles')
            ->will($this->returnValue(array()));

        $annotationPass = new AnnotationConfigurationPass();
        $annotationPass->process($containerBuilderMock);
    }

    public function testProcess()
    {
        $crontabJobClassName = 'Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job\CrontabJob';
        $schedulerJobClassName = 'Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job\SchedulerJob';

        $crontabJobDefinition = new Definition();
        $crontabJobDefinition->setClass($crontabJobClassName);
        $crontabJobDefinition->setArguments(array('@hourly'));
        $crontabJobDefinition->addTag('helthe_chronos.job.crontab');

        $schedulerJobDefinition = new Definition();
        $schedulerJobDefinition->setClass($schedulerJobClassName);
        $schedulerJobDefinition->setArguments(array('@yearly'));
        $schedulerJobDefinition->addTag('helthe_chronos.job.scheduler');

        $metadataFactoryMock = $this->getMock('Metadata\MetadataFactoryInterface');
        $metadataFactoryMock->expects($this->at(0))
            ->method('getMetadataForClass')
            ->with($crontabJobClassName)
            ->will($this->returnValue($this->getClassMetadata($crontabJobClassName)));
        $metadataFactoryMock->expects($this->at(1))
            ->method('getMetadataForClass')
            ->with($schedulerJobClassName)
            ->will($this->returnValue($this->getClassMetadata($schedulerJobClassName)));

        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->at(0))
            ->method('getParameter')
            ->with('helthe_chronos.enable_annotations')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->at(1))
            ->method('getParameter')
            ->with('kernel.bundles')
            ->will($this->returnValue(array('Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\HeltheChronosTestBundle')));
        $containerBuilderMock->expects($this->at(2))
            ->method('get')
            ->with('helthe_chronos.metadata.metadata_factory')
            ->will($this->returnValue($metadataFactoryMock));
        $containerBuilderMock->expects($this->at(3))
            ->method('addResource');
        $containerBuilderMock->expects($this->at(4))
            ->method('setDefinition')
            ->with('helthe.bundle.chronos_bundle.tests.fixture.bundle.test_bundle.job.crontab_job', $this->equalTo($crontabJobDefinition));
        $containerBuilderMock->expects($this->at(5))
            ->method('addResource');
        $containerBuilderMock->expects($this->at(6))
            ->method('setDefinition')
            ->with('helthe.bundle.chronos_bundle.tests.fixture.bundle.test_bundle.job.scheduler_job', $this->equalTo($schedulerJobDefinition));

        $annotationPass = new AnnotationConfigurationPass();
        $annotationPass->process($containerBuilderMock);
    }

    /**
     * Get class metadata for the given class.
     *
     * @param string $className
     *
     * @return ClassHierarchyMetadata
     */
    private function getClassMetadata($className)
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $metadata = new ClassHierarchyMetadata();
        $classMetadata = $driver->loadMetadataForClass(new \ReflectionClass($className));

        $metadata->addClassMetadata($classMetadata);

        return $metadata;
    }
}

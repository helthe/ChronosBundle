<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\DependencyInjection;

use Helthe\Bundle\ChronosBundle\DependencyInjection\HeltheChronosExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HeltheChronosExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithDefaults()
    {
        $container = $this->getContainerBuilder();
        $extension = new HeltheChronosExtension();

        $extension->load(array(), $container);

        $this->assertFalse($container->getParameter('helthe_chronos.enable_annotations'));
        $this->assertFalse($container->getDefinition('helthe_chronos.job.run_command')->hasTag('helthe_chronos.job.crontab'));
    }

    public function testLoadWithAnnotations()
    {
        $container = $this->getContainerBuilder();
        $extension = new HeltheChronosExtension();
        $configs = array(array(
          'enable_annotations' => true
        ));

        $extension->load($configs, $container);

        $this->assertTrue($container->getParameter('helthe_chronos.enable_annotations'));
    }

    public function testLoadWithRunJob()
    {
        $container = $this->getContainerBuilder();
        $extension = new HeltheChronosExtension();
        $configs = array(array(
          'crontab' => array(
              'run_job' => true
          ),
        ));

        $extension->load($configs, $container);

        $this->assertTrue($container->getDefinition('helthe_chronos.job.run_command')->hasTag('helthe_chronos.job.crontab'));
    }

    /**
     * Get a ContainerBuilder instance.
     *
     * @return ContainerBuilder
     */
    private function getContainerBuilder()
    {
        $container = new ContainerBuilder();

        $container->setParameter('kernel.cache_dir', sys_get_temp_dir());

        return $container;
    }
}

<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle;

use Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler\AddCrontabJobsPass;
use Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler\AddSchedulerJobsPass;
use Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler\AnnotationConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * HeltheChronosBundle
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HeltheChronosBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $config = $container->getCompiler()->getPassConfig();
        $passes = $config->getBeforeOptimizationPasses();
        array_unshift($passes, new AnnotationConfigurationPass());
        $config->setBeforeOptimizationPasses($passes);

        $container->addCompilerPass(new AddCrontabJobsPass());
        $container->addCompilerPass(new AddSchedulerJobsPass());
    }
}

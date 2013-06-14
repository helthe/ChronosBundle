<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass registers the jobs in the Crontab.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AddSchedulerJobsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('helthe_chronos.job_scheduler')) {
            return;
        }

        $jobs = array();

        foreach ($container->findTaggedServiceIds('helthe_chronos.job.scheduler') as $id => $tags) {
            $jobs[] = new Reference($id);
        }

        $definition = $container->getDefinition('helthe_chronos.job_scheduler');
        $definition->replaceArgument(0, $jobs);
    }
}

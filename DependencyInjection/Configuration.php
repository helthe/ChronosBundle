<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the general configuration information for HeltheChronosBundle.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('helthe_chronos');

        $rootNode
            ->children()
                ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/helthe_chronos')->end()
                ->arrayNode('crontab')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_user')->defaultNull()->cannotBeEmpty()->end()
                        ->scalarNode('executable')->defaultValue('/usr/bin/crontab')->cannotBeEmpty()->end()
                        ->booleanNode('run_job')->defaultFalse()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->booleanNode('enable_annotations')->defaultFalse()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}

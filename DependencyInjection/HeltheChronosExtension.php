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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages HeltheChronosBundle configuration.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class HeltheChronosExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->loadCrontabConfiguration($container, $config['crontab']);

        $container->setParameter('helthe_chronos.enable_annotations', $config['enable_annotations']);

        $this->configureMetadataCache($container, $config['cache_dir'].'/metadata');
    }

    /**
     * Load crontab configurations.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function loadCrontabConfiguration(ContainerBuilder $container, array $config)
    {
        $container->setParameter('helthe_chronos.crontab.executable', $config['executable']);
        $container->setParameter('helthe_chronos.crontab.default_user', $config['default_user']);

        if ($config['run_job']) {
            $container->getDefinition('helthe_chronos.job.run_command')->addTag('helthe_chronos.job.crontab');
        }
    }

    /**
     * Configures the metadata cache directory,.
     *
     * @param ContainerBuilder $container
     * @param string           $cacheDir
     *
     * @throws RuntimeException
     */
    private function configureMetadataCache(ContainerBuilder $container, $cacheDir)
    {
        $filesystem = new Filesystem();
        $cacheDir = $container->getParameterBag()->resolveValue($cacheDir);

        // Cache directory needs to be cleared for metadata to update.
        $filesystem->remove($cacheDir);

        if (!$filesystem->exists($cacheDir)) {
            $filesystem->mkdir($cacheDir);
        }

        $container->getDefinition('helthe_chronos.metadata.cache.file_cache')->replaceArgument(0, $cacheDir);
    }
}

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

use Helthe\Bundle\ChronosBundle\Exception\RuntimeException;
use Helthe\Bundle\ChronosBundle\Metadata\ClassMetadata;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;

/**
 * This compiler pass creates cron job services from annotations.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AnnotationConfigurationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('helthe_chronos.enable_annotations')) {
            return;
        }

        $directories = $this->getDirectories($container);

        if (empty($directories)) {
            return;
        }

        $factory = $container->get('helthe_chronos.metadata.metadata_factory');

        foreach ($this->getFiles($directories) as $file) {
            $container->addResource(new FileResource($file));
            require_once $file;

            $className = $this->getClassName($file);
            $metadata = $factory->getMetadataForClass($className);

            if (null === $metadata) {
                continue;
            }

            $classMetadata = end($metadata->classMetadata);

            $container->setDefinition($classMetadata->id, $this->convertClassMetadata($classMetadata));
        }
    }

    /**
     * Converts the class metadata into the service definition.
     *
     * @param ClassMetadata $classMetadata
     *
     * @return Definition
     */
    private function convertClassMetadata(ClassMetadata $classMetadata)
    {
        $definition = new Definition();

        $definition->setClass($classMetadata->name);
        $definition->setArguments(array($classMetadata->expression));
        $definition->addTag('helthe_chronos.job.' . $classMetadata->type);

        return $definition;
    }

    /**
     * Get the class name from the file. Only supports one namespaced class per file.
     *
     * @param string $filename
     *
     * @return string the fully qualified class name
     *
     * @throws RuntimeException if the class name cannot be extracted
     */
    private function getClassName($filename)
    {
        $src = file_get_contents($filename);

        if (!preg_match('/\bnamespace\s+([^;]+);/s', $src, $match)) {
            throw new RuntimeException(sprintf('Namespace could not be determined for file "%s".', $filename));
        }
        $namespace = $match[1];

        if (!preg_match('/\bclass\s+([^\s]+)\s+(?:extends|implements|{)/s', $src, $match)) {
            throw new RuntimeException(sprintf('Could not extract class name from file "%s".', $filename));
        }

        return $namespace.'\\'.$match[1];
    }

    /**
     * Get all the directories to scan for annotations.
     *
     * @param ContainerBuilder $container
     *
     * @return string
     */
    private function getDirectories(ContainerBuilder $container)
    {
        $directories = array();
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            $directory = dirname($reflection->getFileName()) . '/Job';

            if (file_exists($directory)) {
                $directories[] = $directory;
            }
        }

        return $directories;
    }

    /**
     * Get all php files from the given directories.
     *
     * @param array $directories
     *
     * @return array
     */
    private function getFiles(array $directories)
    {
        $finder = new Finder();

        $finder->files()->name('*.php')->contains('Helthe\Bundle\ChronosBundle\Annotation')->in($directories)->ignoreVCS(true);

        return array_map('realpath', array_keys(iterator_to_array($finder)));
    }
}

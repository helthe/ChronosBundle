<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Helthe\Bundle\ChronosBundle\Annotation\CronJob;
use Helthe\Bundle\ChronosBundle\Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;

/**
 * Loads CronJob annotation and converts it to metadata.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Load the annotation metadata for class.
     *
     * @param \ReflectionClass $class
     *
     * @return ClassMetatdata|null
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = null;

        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof CronJob) {
                $metadata = $this->generateCronJobMetadata($class, $annotation);
            }
        }

        return $metadata;
    }

    /**
     * Generate metadata for CronJob annotation.
     *
     * @param \ReflectionClass $class
     * @param CronJob          $annotation
     *
     * @return ClassMetadata
     */
    private function generateCronJobMetadata(\ReflectionClass $class, CronJob $annotation)
    {
        $className = $class->getName();
        $metadata = new ClassMetadata($className);

        if (null === $metadata->id) {
            $metadata->id = $this->generateId($className);
        }

        $metadata->expression = $annotation->expression;
        $metadata->type = $annotation->type;

        return $metadata;
    }

    /**
     * Generate a service id from class name.
     *
     * @param string $className
     *
     * @return string
     */
    private function generateId($className)
    {
        $id = preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $className);

        return strtolower(strtr($id, '\\', '.'));
    }
}

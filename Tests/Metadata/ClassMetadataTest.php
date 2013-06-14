<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\Metadata;

use Helthe\Bundle\ChronosBundle\Metadata\ClassMetadata;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ClassMetadata::serialize
     * @covers ClassMetadata::unserialize
     */
    public function testSerializeUnserialize()
    {
        $classMetadata = new ClassMetadata('Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job\CrontabJob');
        $classMetadata->expression = '@hourly';
        $classMetadata->id = 'crontab_job';

        $this->assertEquals($classMetadata, unserialize(serialize($classMetadata)));
    }
}

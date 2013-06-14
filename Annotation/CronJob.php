<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Annotation;

use Helthe\Bundle\ChronosBundle\Exception\InvalidOptionException;
use Helthe\Bundle\ChronosBundle\Exception\MissingOptionException;

/**
 * Annotation to define a cron job service.
 *
 * @Annotation
 * @Target("CLASS")
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
final class CronJob
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $expression;
    /**
     * @var string
     */
    public $type = 'scheduler';

    /**
     * Constructor.
     *
     * @param array $options
     *
     * @throws InvalidOptionException
     * @throws MissingOptionException
     */
    public function __construct(array $options)
    {
        if (isset($options['value'])) {
            $options['expression'] = $options['value'];
            unset($options['value']);
        }

        if (!isset($options['expression'])) {
            throw new MissingOptionException('The option "expression" must be set in CronJob annotation.');
        }

        foreach ($options as $option => $value) {
            $this->$option = $value;
        }

        if ('crontab' != $this->type && 'scheduler' != $this->type) {
            throw new InvalidOptionException(sprintf('The option "type" must be either "crontab" or "scheduler". "%s" given.', $this->type));
        }
    }

    /**
     * Unsupported operation.
     *
     * @throws InvalidOptionException
     */
    public function __set($option, $value)
    {
        throw new InvalidOptionException(sprintf('The option "%s" does not exist in CronJob annotation.', $option));
    }
}

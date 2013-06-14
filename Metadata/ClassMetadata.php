<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Metadata;

use Metadata\ClassMetadata as BaseClassMetadata;

/**
 * Represents the configured cron job.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class ClassMetadata extends BaseClassMetadata
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
    public $type;

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->expression,
            $this->type,
            parent::serialize(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->id,
            $this->expression,
            $this->type,
            $parentStr
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}

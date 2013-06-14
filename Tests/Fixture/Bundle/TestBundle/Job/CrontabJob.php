<?php

/*
 * This file is part of the Helthe Chronos Bundle package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Bundle\ChronosBundle\Tests\Fixture\Bundle\TestBundle\Job;

use Helthe\Bundle\ChronosBundle\Annotation\CronJob;
use Helthe\Component\Chronos\Job\CommandJob;

/**
 * @author Carl Alexander <carlalexander@helthe.co>
 *
 * @CronJob("@hourly", type="crontab")
 */
class CrontabJob extends CommandJob
{

}

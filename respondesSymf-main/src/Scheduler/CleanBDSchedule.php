<?php

namespace App\Scheduler;

use App\Message\CleanBD;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule('CleanBDTask')]
final class CleanBDSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                // @TODO - Modify the frequency to suite your needs
                RecurringMessage::every('1 second', new CleanBD()),
            )
            ->stateful($this->cache,'clean_bd_state')
        ;
    }
}
//php bin/console messenger:consume scheduler_CleanBDTask --verbose

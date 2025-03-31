<?php

namespace App\Scheduler;

use App\Message\DesactiveQuestion;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule('DesactiveQuestionTask')]
final class DesactiveQuestionSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                
                RecurringMessage::every('5 second', new DesactiveQuestion()),
            )
            ->stateful($this->cache,'desactive_question_state')
        ;
    }
}

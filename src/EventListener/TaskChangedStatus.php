<?php

namespace App\EventListener;

use App\Entity\Task;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class TaskChangedStatus
{
    public function postLoad(Task $task, LifecycleEventArgs $event)
    {
        $manager = $event->getObjectManager();

        $deadline = $task->getDeadline();
        $dateTime = new \DateTime();

        if ($deadline instanceof \DateTimeInterface && $deadline->format('Y-m-d') < $dateTime->format('Y-m-d')) {
            $task->setStatus(Task::STATUS_INACTIVE);
            $manager->persist($task);
            $manager->flush();
        }
    }
}

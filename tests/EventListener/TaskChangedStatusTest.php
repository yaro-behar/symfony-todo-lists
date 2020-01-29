<?php

namespace App\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\EventListener\TaskChangedStatus;
use App\Entity\Task;

class TaskChangedStatusTest extends TestCase
{
    public function testPostLoad()
    {
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));

        $task = new Task();
        $task->setStatus(Task::STATUS_ACTIVE);
        $task->setDeadline($deadline);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Task::class));
        $objectManager->expects($this->once())
            ->method('flush');

        $event = $this->createMock(LifecycleEventArgs::class);
        $event->expects($this->once())
            ->method('getObjectManager')
            ->will($this->returnValue($objectManager));

        (new TaskChangedStatus())->postLoad($task, $event);

        $this->assertEquals(Task::STATUS_INACTIVE, $task->getStatus());
    }
}

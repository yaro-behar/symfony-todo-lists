<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Entity\Task;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private const PROJECT_TASKS_NUMBER = 2;

    public function load(ObjectManager $manager)
    {
        /** @var ProjectRepository $repository */
        $repository = $manager->getRepository(Project::class);

        /** @var Project[] $projects */
        $projects = $repository->findAll();

        for ($i = 0; $i < count($projects); $i++) {
            for ($j = 1; $j <= self::PROJECT_TASKS_NUMBER; $j++) {
                $task = new Task();
                $task->setName("Task #{$j}");
                $task->setProject($projects[$i]);
                $manager->persist($task);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProjectFixtures::class];
    }
}

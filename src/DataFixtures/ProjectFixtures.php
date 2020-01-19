<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Project;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    private const USER_PROJECTS_NUMBER = 2;

    public function load(ObjectManager $manager)
    {
        /** @var UserRepository $repository */
        $repository = $manager->getRepository(User::class);

        /** @var User[] $users */
        $users = $repository->findAll();

        for ($i = 0; $i < count($users); $i++) {
            for ($j = 1; $j <= self::USER_PROJECTS_NUMBER; $j++) {
                $project = new Project();
                $project->setName("Project {$j}");
                $project->setUser($users[$i]);
                $manager->persist($project);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}

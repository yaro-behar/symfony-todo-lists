<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Project;

class ProjectControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsForUnauthorizedUsers
     */
    public function testRedirectForUnauthorizedUsers(string $url)
    {
        $client = self::createClient();

        $client->followRedirects();

        $client->request('GET', $url);

        $this->assertContains('Please sign in', $client->getResponse()->getContent());
    }

    public function getUrlsForUnauthorizedUsers()
    {
        return [
            ['/'],
            ['/logout']
        ];
    }

    public function testShowProjects()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        $this->assertContains('SIMPLE TODO LISTS', $client->getResponse()->getContent());
    }

    public function testCreateProject()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        $projectRepository = $client->getContainer()->get('doctrine')->getRepository(Project::class);

        $projectsBeforeInsert = $projectRepository->count([]);

        $client->xmlHttpRequest('GET', '/project/create');

        $projectsAfterInsert = $projectRepository->count([]);

        $this->assertEquals($projectsBeforeInsert + 1, $projectsAfterInsert);
    }

    public function testUpdateProject()
    {
        // TODO: implement
    }

    public function testDeleteProject()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        $projectRepository = $client->getContainer()->get('doctrine')->getRepository(Project::class);

        $projectsBeforeDelete = $projectRepository->count([]);

        $client->xmlHttpRequest('GET', '/project/delete/' . $projectRepository->findOneBy([])->getId());

        $projectsAfterDelete = $projectRepository->count([]);

        $this->assertEquals($projectsBeforeDelete - 1, $projectsAfterDelete);
    }

    public function testDeleteNonExistentProject()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $client->getContainer()
            ->get('doctrine')
            ->getRepository(Project::class)
            ->createQueryBuilder('p');
        $ids = array_column($queryBuilder->select('p.id')->getQuery()->getArrayResult(), 'id');

        $nonExistentId = 1;
        while (in_array($nonExistentId, $ids)) {
            $nonExistentId++;
        }

        $client->xmlHttpRequest('GET', '/project/delete/' . $nonExistentId);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    private function login(KernelBrowser $client)
    {
        $form = $client->getCrawler()->selectButton('Login')->form();
        $form['_username'] = 'user1@domain.com';
        $form['_password'] = 'qwerty';

        $client->submit($form);
        $client->getResponse()->isRedirection() && $client->followRedirect();
    }
}

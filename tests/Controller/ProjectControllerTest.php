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
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        $projectRepository = $client->getContainer()->get('doctrine')->getRepository(Project::class);

        $id = $projectRepository->findOneBy([])->getId();

        $name = $this->generateRandomString();

        $client->xmlHttpRequest(
            'POST',
            '/project/update',
            [
                'project_id' => $id,
                'project_name' => $name
            ]
        );

        $this->assertEquals($name, $projectRepository->find($id)->getName());
    }

    public function testUpdateNonExistentProject()
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

        $client->xmlHttpRequest(
            'POST',
            '/project/update/',
            [
                'project_id' => $nonExistentId,
                'project_name' => 'new_name'
            ]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testUpdateProjectWithInvalidName()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->login($client);

        $projectRepository = $client->getContainer()->get('doctrine')->getRepository(Project::class);

        $client->xmlHttpRequest(
            'POST',
            '/project/update',
            [
                'project_id' => $projectRepository->findOneBy([])->getId(),
                'project_name' => '%!!$invalid_name$!!%'
            ]
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
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

    private function generateRandomString(int $length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

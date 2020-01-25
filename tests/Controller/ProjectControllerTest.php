<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
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

        $projectRepository  = $client->getContainer()->get('doctrine')->getRepository(Project::class);

        $projectsBeforeInsert = $projectRepository->count([]);

        $client->xmlHttpRequest('GET', '/project/create');

        $projectsAfterInsert = $projectRepository->count([]);

        $this->assertEquals($projectsBeforeInsert + 1, $projectsAfterInsert);
    }

    public function testDeleteProject()
    {

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

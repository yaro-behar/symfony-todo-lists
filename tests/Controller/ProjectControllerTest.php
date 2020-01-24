<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testRedirectForUnauthorizedUsers(string $url)
    {
        $client = self::createClient();

        $client->request('GET', $url);

        while ($client->getResponse()->isRedirection()) {
            $client->followRedirect();
        }

        $this->assertContains('Please sign in', $client->getResponse()->getContent());
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/logout']
        ];
    }

    public function testShowProjectList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();
        $form['_username'] = 'user1@domain.com';
        $form['_password'] = 'qwerty';

        $client->submit($form);

        $client->getResponse()->isRedirection() && $client->followRedirect();

        $this->assertContains('SIMPLE TODO LISTS', $client->getResponse()->getContent());
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 29.01.2016
 * Time: 23:36
 */

namespace AppBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class AdminFunctionalTest
 * @package AppBundle\Tests\Admin
 */
class AdminFunctionalTest extends WebTestCase
{
    protected function setUp()
    {
        $this->runCommand('doctrine:database:create', ['--if-not-exists' => true]);
        $this->runCommand('doctrine:schema:drop', ['--force' => true]);
        $this->runCommand('doctrine:schema:update', ['--force' => true]);

        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadAdminData'
        ]);
    }

    protected function tearDown()
    {
        $this->runCommand('doctrine:schema:drop', ['--force' => true]);

        parent::tearDown();
    }

    /**
     * @group admin
     *
     * @return Client
     */
    public function testAdminLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');

        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $client->request('GET', '/admin/dashboard');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $client;
    }

    /**
     * @group admin
     * @depends testAdminLogin
     *
     * @param Client $client
     */
    public function testAdminLogout(Client $client)
    {
        $client->request('GET', '/admin/logout');
        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $client->request('GET', '/admin/dashboard');
        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 29.01.2016
 * Time: 23:36
 */

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class AdminControllerTest
 * @package AppBundle\Tests\Controller
 */
class AdminControllerTest extends WebTestCase
{

    protected function setUp()
    {
        self::bootKernel();

        $consolePath = static::$kernel->getRootDir();

        exec("php $consolePath/console doctrine:database:create --env=test --if-not-exists");
        exec("php $consolePath/console doctrine:schema:drop --env=test --force");
        exec("php $consolePath/console doctrine:schema:update --env=test --force");

        $this->loadFixtures([
            'AppBundle\DataFixtures\ORM\LoadAdminData'
        ]);
    }

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
     * @depends testAdminLogin
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
<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 29.01.2016
 * Time: 23:36
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        exec("php $consolePath/console doctrine:database:drop --env=test");
        exec("php $consolePath/console doctrine:database:create --env=test --if-not-exists");
        exec("php $consolePath/console doctrine:schema:drop --env=test --dump-sql --force");
        exec("php $consolePath/console doctrine:schema:update --env=test --dump-sql --force");
        exec("php $consolePath/console doctrine:fixtures:load --env=test -n");
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
    }
}
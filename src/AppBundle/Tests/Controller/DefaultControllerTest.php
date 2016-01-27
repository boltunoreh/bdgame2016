<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use AppBundle\DataFixtures\ORM\LoadAdminData;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package AppBundle\Tests\Controller
 *
 * @see http://symfony.com/blog/new-in-symfony-2-7-phpunit-bridge
 */
class DefaultControllerTest extends WebTestCase
{
    protected static $application;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $console = static::$kernel->getRootDir();

        exec("php $console/console doctrine:database:create --env=test --if-not-exists");
        exec("php $console/console doctrine:schema:drop --env=test --dump-sql --force");
        exec("php $console/console doctrine:schema:update --env=test --dump-sql --force");
        exec("php $console/console doctrine:fixtures:load --env=test -n");

    }



//    public function testIndex()
//    {
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/');
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
//    }

    public function testAdminAuth()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');

        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = 'admin';
        $form['_password'] = 'admin';

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $client->request('GET', '/admin/dashboard');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}

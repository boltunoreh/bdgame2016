<?php

namespace AppBundle\Tests\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package AppBundle\Tests\Controller
 *
 * @see http://symfony.com/blog/new-in-symfony-2-7-phpunit-bridge
 */
class DefaultControllerTest extends WebTestCase
{
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

        $admin = new User();
        $admin->setEmail("adminl@gmail.com");
        $admin->setUsername("admin");
        $admin->setPlainPassword("admin");
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);

        $this->em->persist($admin);
        $this->em->flush();
    }


    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testAdminAuth()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/login');

        $form = $crawler->selectButton('_submit')->form();

        $form['_username'] = 'admin';
        $form['_password'] = 'admin3';

        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $client->followRedirect();

        print '<pre>' . print_r($client->getResponse()->getContent(), true) . '</pre>';


        die;

//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());

    }
}

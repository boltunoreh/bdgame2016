<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 22.01.2016
 * Time: 22:16
 */

namespace AppBundle\Tests\Generic;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductionStateTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    protected $kernelRootDir;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->client = self::createClient();

        $this->container = static::$kernel->getContainer();
        $this->kernelRootDir = $this->container->get('kernel')->getRootDir();

    }

    /**
     * @group production
     */
    public function testFaviconIsExists()
    {
        $this->assertFileExists($this->kernelRootDir . "/../web/favicon.ico");
    }

    /**
     * @group production
     */
    public function test404()
    {
        $this->client->request('GET', sprintf('/porno-hardcore/%s', microtime(true)));

        $this->assertTrue($this->client->getResponse()->isNotFound());
    }


    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        unset($this->client, $this->container, $this->kernelRootDir);
        parent::tearDown();
    }

}

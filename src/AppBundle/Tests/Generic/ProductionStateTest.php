<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 22.01.2016
 * Time: 22:16
 */

namespace AppBundle\Tests\Generic;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ProductionStateTest extends WebTestCase
{
    /**
     * @group production
     */
    public function testFaviconIsExists()
    {
        static::bootKernel();

        $kernelRootDir = static::$kernel
            ->getContainer()
            ->get('kernel')
            ->getRootDir();

        $this->assertFileExists($kernelRootDir . '/../web/favicon.ico');
    }

    /**
     * @group production
     */
    public function test404()
    {
        $client = static::createClient();

        $client->request('GET', sprintf('/porno-hardcore/%s', microtime(true)));

        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
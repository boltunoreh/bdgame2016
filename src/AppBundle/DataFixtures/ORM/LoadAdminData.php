<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 27.01.2016
 * Time: 22:59
 */

namespace AppBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAdminData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail("admin2l@gmail.com");
        $admin->setUsername("admin2");
        $admin->setPlainPassword("admin2");
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);

        $manager->persist($admin);
        $manager->flush();
    }


}
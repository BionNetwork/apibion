<?php

namespace BiBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\User;
use BiBundle\Entity\UserRole;
use BiBundle\Entity\UserStatus;

class UserServiceTest extends KernelTestCase implements ContainerAwareInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function setUp()
    {
        self::bootKernel();
        $this->setContainer(static::$kernel->getContainer());

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')->getManager();
    }

    /**
     * Create user test
     *
     * @return User
     */
    protected function createUser()
    {
        $userStatus = $this->em->getRepository('BiBundle:UserStatus')->findOneBy(['code' => 'active']);
        $userRole = $this->em->getRepository('BiBundle:UserRole')->findOneBy(['name' => UserRole::ROLE_USER]);
        $organization = $this->em->getRepository('BiBundle:Organization')->findOneBy(['name' => 'Эттон']);


        $user = new User();
        $user->setStatus($userStatus);
        $user->setRole($userRole);
        $user->setOrganization($organization);
        $user->setFirstname('Тестовый юзер');
        $user->setLastname('Тестовый юзер');
        $user->setMiddlename('Тестовый юзер');
        $user->setLogin('test');
        $user->setEmail('testuser@test.com');
        $user->setPhone('79999999997');

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }


    public function testUserSearchSuccess()
    {
        $user = $this->createUser();
        $userService = $this->container->get('user.service');
        $result = $userService->search('Тест');

        $expected[] = [
            "id" => $user->getId(),
            "firstname" => "Тестовый юзер",
            "middlename" => "Тестовый юзер",
            "lastname" => "Тестовый юзер",
            "position" => null,
            "organization" => $user->getOrganization()->getName(),
            "department" => null,
            "photo" => null
        ];
        $this->assertTrue($expected == $result);
    }


    protected function tearDown()
    {
        $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\User u WHERE u.login NOT IN (:login)");
        $q->setParameter('login', ['demo', 'rose']);
        $q->execute();
        $this->em->close();
        parent::tearDown();
    }
}

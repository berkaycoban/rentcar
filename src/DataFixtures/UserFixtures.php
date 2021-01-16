<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("berkay@rentcar.com")
            ->setName("Berkay")
            ->setSurname("Coban")
            ->setPassword($this->passwordEncoder->encodePassword($user, '12345678'))
            ->setRoles(["ROLE_SUPER_ADMIN"])
            ->setCreatedAt(new DateTime());

        $manager->persist($user);
        $manager->flush();
    }
}

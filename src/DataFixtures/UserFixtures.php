<?php

namespace App\DataFixtures;

use App\Entity\Company;
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
        $company = new Company();
        $company->setName("RentCar AS")
            ->setAddress("Torbali")
            ->setCity("IZMIR")
            ->setEmail("info@rentcar.com")
            ->setCreatedAt(new DateTime());

        $user = new User();
        $user->setEmail("berkay@rentcar.com")
            ->setName("Berkay")
            ->setSurname("Coban")
            ->setPassword($this->passwordEncoder->encodePassword($user, '12345678'))
            ->setRoles(["ROLE_ADMIN", "ROLE_SUPER_ADMIN"])
            ->setCompany($company)
            ->setCreatedAt(new DateTime());

        $customer = new User();
        $customer->setEmail("customer@a.com")
            ->setName("John")
            ->setSurname("Doe")
            ->setPassword($this->passwordEncoder->encodePassword($customer, '12345678'))
            ->setCompany(null)
            ->setCreatedAt(new DateTime());

        $manager->persist($customer);
        $manager->persist($company);
        $manager->persist($user);
        $manager->flush();
    }
}

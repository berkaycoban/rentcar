<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerFixtures extends Fixture
{
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $customer = new User();
        $customer->setEmail("customer@a.com")
            ->setName("John")
            ->setSurname("Doe")
            ->setPassword($this->passwordEncoder->encodePassword($customer, '12345678'))
            ->setCompany(null)
            ->setCreatedAt(new DateTime());

        $manager->persist($customer);
        $manager->flush();
    }
}

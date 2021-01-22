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
        foreach ($this->getUserData() as [$fullName, $email, $password, $company, $roles, $reference_name]) {
            $customer = new User();
            $customer->setFullName($fullName)
                ->setEmail($email)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $customer, $password
                ))
                ->setRoles($roles)
                ->setCompany($company)
                ->setCreatedAt(new DateTime());

            if($reference_name){
                $this->addReference((string)$reference_name, $customer);
            }

            $manager->persist($customer);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullName, $email, $password, $company, $roles, $reference_name];
            ['John Smith', 'user@a.com', '12345678', null, ['ROLE_USER'], 'customer1'],
            ['Rhonda Jordan', 'user1@a.com', '12345678', null, ['ROLE_USER'], 'customer2'],
            ['John Doe', 'user2@a.com', '12345678', null, ['ROLE_USER'], 'customer3'],
            ['Aytur Doe', 'user3@a.com', '12345678', null, ['ROLE_USER'], ''],
            ['Doge Coin', 'user4@a.com', '12345678', null, ['ROLE_USER'], ''],
            ['Holo bar', 'user5@a.com', '12345678', null, ['ROLE_USER'], ''],
        ];
    }
}
